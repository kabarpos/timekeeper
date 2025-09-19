#!/bin/bash

# ==========================================
# TimeKeeper Production Optimization Script
# ==========================================
# Script untuk optimasi otomatis setelah deployment

set -e

echo "🚀 Starting TimeKeeper Production Optimization..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if running as www-data or root
if [[ $EUID -ne 0 ]] && [[ $(whoami) != "www-data" ]]; then
    print_error "This script should be run as root or www-data user"
    exit 1
fi

# Set project directory
PROJECT_DIR="/var/www/timekeeper"
cd $PROJECT_DIR

print_status "Current directory: $(pwd)"

# 1. Clear all caches
print_status "Clearing application caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# 2. Optimize for production
print_status "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 3. Optimize Composer autoloader
print_status "Optimizing Composer autoloader..."
composer dump-autoload --optimize --no-dev

# 4. Set proper permissions
print_status "Setting proper file permissions..."
chown -R www-data:www-data $PROJECT_DIR
chmod -R 755 $PROJECT_DIR
chmod -R 775 $PROJECT_DIR/storage
chmod -R 775 $PROJECT_DIR/bootstrap/cache

# 5. Create symbolic link for storage
print_status "Creating storage symbolic link..."
php artisan storage:link

# 6. Optimize database
print_status "Running database optimizations..."
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder --force

# 7. Queue optimization
print_status "Restarting queue workers..."
php artisan queue:restart

# 8. Check Redis connection
print_status "Testing Redis connection..."
if php artisan tinker --execute="Redis::ping();" > /dev/null 2>&1; then
    print_status "Redis connection: OK"
else
    print_warning "Redis connection failed - check Redis server"
fi

# 9. Check database connection
print_status "Testing database connection..."
if php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; then
    print_status "Database connection: OK"
else
    print_error "Database connection failed - check database configuration"
    exit 1
fi

# 10. Warm up application
print_status "Warming up application..."
curl -s -o /dev/null -w "%{http_code}" http://localhost/health || print_warning "Health check endpoint not responding"

# 11. Generate sitemap (if applicable)
print_status "Generating application cache..."
php artisan tinker --execute="
    \App\Services\CacheService::remember('warmup_cache', function() {
        return 'Application warmed up at ' . now();
    });
"

# 12. Security checks
print_status "Running security checks..."

# Check .env file permissions
if [[ $(stat -c %a .env) != "600" ]]; then
    print_warning "Setting .env file permissions to 600"
    chmod 600 .env
fi

# Check for debug mode
if grep -q "APP_DEBUG=true" .env; then
    print_error "DEBUG MODE IS ENABLED! This is a security risk in production."
    print_error "Please set APP_DEBUG=false in your .env file"
fi

# Check for default APP_KEY
if grep -q "base64:GENERATE_NEW_KEY" .env; then
    print_error "Default APP_KEY detected! Please generate a new key with: php artisan key:generate"
fi

# 13. Performance optimizations
print_status "Applying performance optimizations..."

# OPcache status check
if php -m | grep -q "Zend OPcache"; then
    print_status "OPcache is enabled"
else
    print_warning "OPcache is not enabled - consider enabling it for better performance"
fi

# 14. Log rotation setup
print_status "Setting up log rotation..."
cat > /tmp/timekeeper-logrotate << EOF
$PROJECT_DIR/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        php $PROJECT_DIR/artisan cache:clear > /dev/null 2>&1 || true
    endscript
}
EOF

if [[ $EUID -eq 0 ]]; then
    mv /tmp/timekeeper-logrotate /etc/logrotate.d/timekeeper
    print_status "Log rotation configured"
else
    print_warning "Cannot setup log rotation - run as root to enable this feature"
fi

# 15. Create backup script
print_status "Creating backup script..."
cat > $PROJECT_DIR/backup.sh << 'EOF'
#!/bin/bash
# TimeKeeper Backup Script

BACKUP_DIR="/backup/timekeeper"
DATE=$(date +%Y%m%d_%H%M%S)
PROJECT_DIR="/var/www/timekeeper"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u timekeeper_user -p timekeeper_prod > $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C $PROJECT_DIR storage public/uploads .env

# Cleanup old backups (keep 30 days)
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete

echo "Backup completed: $DATE"
EOF

chmod +x $PROJECT_DIR/backup.sh

# 16. Health check script
print_status "Creating health check script..."
cat > $PROJECT_DIR/health-check.sh << 'EOF'
#!/bin/bash
# TimeKeeper Health Check Script

PROJECT_DIR="/var/www/timekeeper"
cd $PROJECT_DIR

echo "=== TimeKeeper Health Check ==="
echo "Timestamp: $(date)"
echo

# Check application status
echo "1. Application Status:"
if curl -s -f http://localhost/health > /dev/null; then
    echo "   ✅ Application is responding"
else
    echo "   ❌ Application is not responding"
fi

# Check database
echo "2. Database Status:"
if php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; then
    echo "   ✅ Database connection OK"
else
    echo "   ❌ Database connection failed"
fi

# Check Redis
echo "3. Redis Status:"
if php artisan tinker --execute="Redis::ping();" > /dev/null 2>&1; then
    echo "   ✅ Redis connection OK"
else
    echo "   ❌ Redis connection failed"
fi

# Check queue workers
echo "4. Queue Workers:"
WORKERS=$(supervisorctl status timekeeper-worker:* 2>/dev/null | grep RUNNING | wc -l)
if [[ $WORKERS -gt 0 ]]; then
    echo "   ✅ $WORKERS queue workers running"
else
    echo "   ❌ No queue workers running"
fi

# Check disk space
echo "5. Disk Space:"
DISK_USAGE=$(df -h $PROJECT_DIR | awk 'NR==2 {print $5}' | sed 's/%//')
if [[ $DISK_USAGE -lt 80 ]]; then
    echo "   ✅ Disk usage: ${DISK_USAGE}%"
else
    echo "   ⚠️  High disk usage: ${DISK_USAGE}%"
fi

# Check memory usage
echo "6. Memory Usage:"
MEMORY_USAGE=$(free | grep Mem | awk '{printf "%.0f", $3/$2 * 100.0}')
if [[ $MEMORY_USAGE -lt 80 ]]; then
    echo "   ✅ Memory usage: ${MEMORY_USAGE}%"
else
    echo "   ⚠️  High memory usage: ${MEMORY_USAGE}%"
fi

echo
echo "=== End Health Check ==="
EOF

chmod +x $PROJECT_DIR/health-check.sh

# 17. Final checks
print_status "Running final checks..."

# Check if all required directories exist
REQUIRED_DIRS=("storage/logs" "storage/framework/cache" "storage/framework/sessions" "storage/framework/views" "bootstrap/cache")
for dir in "${REQUIRED_DIRS[@]}"; do
    if [[ ! -d "$PROJECT_DIR/$dir" ]]; then
        print_warning "Creating missing directory: $dir"
        mkdir -p "$PROJECT_DIR/$dir"
        chown www-data:www-data "$PROJECT_DIR/$dir"
    fi
done

# Test application endpoints
print_status "Testing critical endpoints..."
ENDPOINTS=("/" "/health")
for endpoint in "${ENDPOINTS[@]}"; do
    HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://localhost$endpoint" || echo "000")
    if [[ $HTTP_CODE -eq 200 ]]; then
        print_status "Endpoint $endpoint: OK ($HTTP_CODE)"
    else
        print_warning "Endpoint $endpoint: Failed ($HTTP_CODE)"
    fi
done

print_status "✅ Production optimization completed!"
print_status ""
print_status "📋 Next steps:"
print_status "1. Run health check: ./health-check.sh"
print_status "2. Setup cron for backups: 0 2 * * * $PROJECT_DIR/backup.sh"
print_status "3. Monitor logs: tail -f storage/logs/laravel.log"
print_status "4. Check performance: /admin/monitoring"
print_status ""
print_status "🔧 Manual tasks remaining:"
print_status "- Configure SSL certificate"
print_status "- Setup monitoring alerts"
print_status "- Configure firewall rules"
print_status "- Test backup and restore procedures"

echo
echo "🎉 TimeKeeper is ready for production!"