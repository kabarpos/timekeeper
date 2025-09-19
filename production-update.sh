#!/bin/bash

# ==========================================
# TimeKeeper Production Update Script
# ==========================================
# Script untuk update otomatis production dengan analisis perubahan file

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Configuration
PROJECT_DIR="/var/www/timekeeper"
BACKUP_DIR="/backup/timekeeper/updates"
LOG_FILE="$PROJECT_DIR/storage/logs/production-update.log"
MAINTENANCE_FILE="$PROJECT_DIR/storage/framework/maintenance.php"

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1" | tee -a "$LOG_FILE"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1" | tee -a "$LOG_FILE"
}

print_analysis() {
    echo -e "${PURPLE}[ANALYSIS]${NC} $1" | tee -a "$LOG_FILE"
}

# Function to log with timestamp
log_with_timestamp() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> "$LOG_FILE"
}

# Function to create backup
create_backup() {
    local backup_name="pre-update-$(date +%Y%m%d_%H%M%S)"
    local backup_path="$BACKUP_DIR/$backup_name"
    
    print_step "Creating backup: $backup_name"
    mkdir -p "$backup_path"
    
    # Backup critical files
    cp -r "$PROJECT_DIR/app" "$backup_path/" 2>/dev/null || true
    cp -r "$PROJECT_DIR/config" "$backup_path/" 2>/dev/null || true
    cp -r "$PROJECT_DIR/database" "$backup_path/" 2>/dev/null || true
    cp -r "$PROJECT_DIR/resources" "$backup_path/" 2>/dev/null || true
    cp -r "$PROJECT_DIR/routes" "$backup_path/" 2>/dev/null || true
    cp "$PROJECT_DIR/.env" "$backup_path/" 2>/dev/null || true
    cp "$PROJECT_DIR/composer.json" "$backup_path/" 2>/dev/null || true
    cp "$PROJECT_DIR/package.json" "$backup_path/" 2>/dev/null || true
    
    # Database backup
    if command -v mysqldump &> /dev/null; then
        print_status "Creating database backup..."
        DB_NAME=$(grep DB_DATABASE "$PROJECT_DIR/.env" | cut -d '=' -f2)
        DB_USER=$(grep DB_USERNAME "$PROJECT_DIR/.env" | cut -d '=' -f2)
        DB_PASS=$(grep DB_PASSWORD "$PROJECT_DIR/.env" | cut -d '=' -f2)
        
        if [[ -n "$DB_NAME" && -n "$DB_USER" ]]; then
            mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$backup_path/database_backup.sql" 2>/dev/null || print_warning "Database backup failed"
        fi
    fi
    
    print_status "Backup created at: $backup_path"
    echo "$backup_path" > "$PROJECT_DIR/storage/last_backup_path.txt"
}

# Function to analyze changed files
analyze_changes() {
    print_step "Analyzing repository changes..."
    
    # Get current commit hash
    local current_commit=$(git rev-parse HEAD)
    local previous_commit_file="$PROJECT_DIR/storage/last_deploy_commit.txt"
    
    if [[ -f "$previous_commit_file" ]]; then
        local previous_commit=$(cat "$previous_commit_file")
        print_analysis "Previous commit: $previous_commit"
        print_analysis "Current commit: $current_commit"
        
        # Get list of changed files
        local changed_files=$(git diff --name-only "$previous_commit" "$current_commit" 2>/dev/null || echo "")
        
        if [[ -z "$changed_files" ]]; then
            print_status "No changes detected since last deployment"
            return 0
        fi
        
        print_analysis "Changed files detected:"
        echo "$changed_files" | while read -r file; do
            if [[ -n "$file" ]]; then
                print_analysis "  - $file"
            fi
        done
        
        # Analyze what needs to be updated based on changed files
        analyze_update_requirements "$changed_files"
    else
        print_warning "No previous deployment record found. Performing full update."
        NEEDS_COMPOSER=true
        NEEDS_NPM=true
        NEEDS_MIGRATION=true
        NEEDS_CACHE_CLEAR=true
        NEEDS_QUEUE_RESTART=true
        NEEDS_CONFIG_CACHE=true
    fi
    
    # Save current commit for next deployment
    echo "$current_commit" > "$previous_commit_file"
}

# Function to determine what needs to be updated
analyze_update_requirements() {
    local changed_files="$1"
    
    # Reset flags
    NEEDS_COMPOSER=false
    NEEDS_NPM=false
    NEEDS_MIGRATION=false
    NEEDS_CACHE_CLEAR=false
    NEEDS_QUEUE_RESTART=false
    NEEDS_CONFIG_CACHE=false
    NEEDS_VIEW_CACHE=false
    NEEDS_ROUTE_CACHE=false
    NEEDS_STORAGE_LINK=false
    
    print_analysis "Determining update requirements..."
    
    while IFS= read -r file; do
        if [[ -n "$file" ]]; then
            case "$file" in
                composer.json|composer.lock)
                    print_analysis "  → Composer dependencies changed"
                    NEEDS_COMPOSER=true
                    NEEDS_CACHE_CLEAR=true
                    ;;
                package.json|package-lock.json)
                    print_analysis "  → NPM dependencies changed"
                    NEEDS_NPM=true
                    ;;
                database/migrations/*)
                    print_analysis "  → Database migrations changed"
                    NEEDS_MIGRATION=true
                    ;;
                config/*)
                    print_analysis "  → Configuration files changed"
                    NEEDS_CONFIG_CACHE=true
                    NEEDS_CACHE_CLEAR=true
                    ;;
                routes/*)
                    print_analysis "  → Route files changed"
                    NEEDS_ROUTE_CACHE=true
                    NEEDS_CACHE_CLEAR=true
                    ;;
                resources/views/*)
                    print_analysis "  → View files changed"
                    NEEDS_VIEW_CACHE=true
                    ;;
                resources/css/*|resources/js/*|vite.config.js|tailwind.config.js)
                    print_analysis "  → Frontend assets changed"
                    NEEDS_NPM=true
                    ;;
                app/*)
                    print_analysis "  → Application code changed"
                    NEEDS_CACHE_CLEAR=true
                    NEEDS_QUEUE_RESTART=true
                    ;;
                .env.example|storage/*)
                    print_analysis "  → Storage or environment template changed"
                    NEEDS_STORAGE_LINK=true
                    ;;
            esac
        fi
    done <<< "$changed_files"
    
    # Summary of required updates
    print_analysis "Update requirements summary:"
    [[ "$NEEDS_COMPOSER" == true ]] && print_analysis "  ✓ Composer update required"
    [[ "$NEEDS_NPM" == true ]] && print_analysis "  ✓ NPM build required"
    [[ "$NEEDS_MIGRATION" == true ]] && print_analysis "  ✓ Database migration required"
    [[ "$NEEDS_CACHE_CLEAR" == true ]] && print_analysis "  ✓ Cache clearing required"
    [[ "$NEEDS_CONFIG_CACHE" == true ]] && print_analysis "  ✓ Config cache rebuild required"
    [[ "$NEEDS_ROUTE_CACHE" == true ]] && print_analysis "  ✓ Route cache rebuild required"
    [[ "$NEEDS_VIEW_CACHE" == true ]] && print_analysis "  ✓ View cache rebuild required"
    [[ "$NEEDS_QUEUE_RESTART" == true ]] && print_analysis "  ✓ Queue restart required"
    [[ "$NEEDS_STORAGE_LINK" == true ]] && print_analysis "  ✓ Storage link update required"
}

# Function to enable maintenance mode
enable_maintenance() {
    print_step "Enabling maintenance mode..."
    php artisan down --refresh=15 --retry=60 --secret="update-$(date +%s)" || print_warning "Failed to enable maintenance mode"
}

# Function to disable maintenance mode
disable_maintenance() {
    print_step "Disabling maintenance mode..."
    php artisan up || print_warning "Failed to disable maintenance mode"
}

# Function to update composer dependencies
update_composer() {
    if [[ "$NEEDS_COMPOSER" == true ]]; then
        print_step "Updating Composer dependencies..."
        composer install --optimize-autoloader --no-dev --no-interaction || {
            print_error "Composer update failed"
            return 1
        }
        composer dump-autoload --optimize || print_warning "Autoloader optimization failed"
    else
        print_status "Skipping Composer update (no changes detected)"
    fi
}

# Function to build frontend assets
build_frontend() {
    if [[ "$NEEDS_NPM" == true ]]; then
        print_step "Building frontend assets..."
        npm ci --production || {
            print_error "NPM install failed"
            return 1
        }
        npm run build || {
            print_error "Frontend build failed"
            return 1
        }
    else
        print_status "Skipping frontend build (no changes detected)"
    fi
}

# Function to run database migrations
run_migrations() {
    if [[ "$NEEDS_MIGRATION" == true ]]; then
        print_step "Running database migrations..."
        php artisan migrate --force || {
            print_error "Database migration failed"
            return 1
        }
    else
        print_status "Skipping migrations (no changes detected)"
    fi
}

# Function to clear and rebuild caches
update_caches() {
    if [[ "$NEEDS_CACHE_CLEAR" == true ]]; then
        print_step "Clearing application caches..."
        php artisan cache:clear || print_warning "Cache clear failed"
        php artisan config:clear || print_warning "Config clear failed"
        php artisan route:clear || print_warning "Route clear failed"
        php artisan view:clear || print_warning "View clear failed"
        php artisan event:clear || print_warning "Event clear failed"
    fi
    
    if [[ "$NEEDS_CONFIG_CACHE" == true ]]; then
        print_step "Rebuilding config cache..."
        php artisan config:cache || print_warning "Config cache failed"
    fi
    
    if [[ "$NEEDS_ROUTE_CACHE" == true ]]; then
        print_step "Rebuilding route cache..."
        php artisan route:cache || print_warning "Route cache failed"
    fi
    
    if [[ "$NEEDS_VIEW_CACHE" == true ]]; then
        print_step "Rebuilding view cache..."
        php artisan view:cache || print_warning "View cache failed"
    fi
    
    # Always rebuild event cache for safety
    php artisan event:cache || print_warning "Event cache failed"
}

# Function to restart queue workers
restart_queues() {
    if [[ "$NEEDS_QUEUE_RESTART" == true ]]; then
        print_step "Restarting queue workers..."
        php artisan queue:restart || print_warning "Queue restart failed"
        
        # Restart supervisor workers if available
        if command -v supervisorctl &> /dev/null; then
            supervisorctl restart timekeeper-worker:* || print_warning "Supervisor restart failed"
        fi
    else
        print_status "Skipping queue restart (no changes detected)"
    fi
}

# Function to update storage links
update_storage() {
    if [[ "$NEEDS_STORAGE_LINK" == true ]]; then
        print_step "Updating storage links..."
        php artisan storage:link || print_warning "Storage link failed"
    fi
}

# Function to set proper permissions
set_permissions() {
    print_step "Setting proper file permissions..."
    chown -R www-data:www-data "$PROJECT_DIR" || print_warning "Permission setting failed"
    chmod -R 755 "$PROJECT_DIR" || print_warning "Permission setting failed"
    chmod -R 775 "$PROJECT_DIR/storage" || print_warning "Storage permission setting failed"
    chmod -R 775 "$PROJECT_DIR/bootstrap/cache" || print_warning "Bootstrap cache permission setting failed"
}

# Function to run health checks
run_health_checks() {
    print_step "Running post-update health checks..."
    
    # Check if application responds
    local health_url="http://localhost/health"
    if curl -s -f "$health_url" > /dev/null; then
        print_status "✓ Application health check passed"
    else
        print_warning "✗ Application health check failed"
    fi
    
    # Check database connection
    if php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; then
        print_status "✓ Database connection check passed"
    else
        print_error "✗ Database connection check failed"
    fi
    
    # Check Redis connection
    if php artisan tinker --execute="Redis::ping();" > /dev/null 2>&1; then
        print_status "✓ Redis connection check passed"
    else
        print_warning "✗ Redis connection check failed"
    fi
    
    # Check queue workers
    if command -v supervisorctl &> /dev/null; then
        local workers=$(supervisorctl status timekeeper-worker:* 2>/dev/null | grep RUNNING | wc -l)
        if [[ $workers -gt 0 ]]; then
            print_status "✓ Queue workers check passed ($workers workers running)"
        else
            print_warning "✗ No queue workers running"
        fi
    fi
}

# Function to rollback on failure
rollback() {
    print_error "Update failed! Initiating rollback..."
    
    local backup_path_file="$PROJECT_DIR/storage/last_backup_path.txt"
    if [[ -f "$backup_path_file" ]]; then
        local backup_path=$(cat "$backup_path_file")
        if [[ -d "$backup_path" ]]; then
            print_step "Restoring from backup: $backup_path"
            
            # Restore files
            cp -r "$backup_path/app" "$PROJECT_DIR/" 2>/dev/null || true
            cp -r "$backup_path/config" "$PROJECT_DIR/" 2>/dev/null || true
            cp -r "$backup_path/database" "$PROJECT_DIR/" 2>/dev/null || true
            cp -r "$backup_path/resources" "$PROJECT_DIR/" 2>/dev/null || true
            cp -r "$backup_path/routes" "$PROJECT_DIR/" 2>/dev/null || true
            cp "$backup_path/.env" "$PROJECT_DIR/" 2>/dev/null || true
            cp "$backup_path/composer.json" "$PROJECT_DIR/" 2>/dev/null || true
            cp "$backup_path/package.json" "$PROJECT_DIR/" 2>/dev/null || true
            
            # Restore database if backup exists
            if [[ -f "$backup_path/database_backup.sql" ]]; then
                print_step "Restoring database..."
                DB_NAME=$(grep DB_DATABASE "$PROJECT_DIR/.env" | cut -d '=' -f2)
                DB_USER=$(grep DB_USERNAME "$PROJECT_DIR/.env" | cut -d '=' -f2)
                DB_PASS=$(grep DB_PASSWORD "$PROJECT_DIR/.env" | cut -d '=' -f2)
                
                if [[ -n "$DB_NAME" && -n "$DB_USER" ]]; then
                    mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$backup_path/database_backup.sql" || print_warning "Database restore failed"
                fi
            fi
            
            # Clear caches after rollback
            php artisan cache:clear || true
            php artisan config:clear || true
            php artisan route:clear || true
            php artisan view:clear || true
            
            print_status "Rollback completed"
        else
            print_error "Backup directory not found: $backup_path"
        fi
    else
        print_error "No backup path found for rollback"
    fi
    
    disable_maintenance
    exit 1
}

# Function to send notification (customize as needed)
send_notification() {
    local status="$1"
    local message="$2"
    
    # Log notification
    log_with_timestamp "NOTIFICATION: $status - $message"
    
    # Add your notification logic here (email, Slack, etc.)
    # Example: curl -X POST -H 'Content-type: application/json' --data '{"text":"'$message'"}' YOUR_WEBHOOK_URL
}

# Main execution function
main() {
    local start_time=$(date +%s)
    
    print_status "=========================================="
    print_status "TimeKeeper Production Update Started"
    print_status "Timestamp: $(date)"
    print_status "=========================================="
    
    # Check if running as appropriate user
    if [[ $EUID -ne 0 ]] && [[ $(whoami) != "www-data" ]]; then
        print_error "This script should be run as root or www-data user"
        exit 1
    fi
    
    # Change to project directory
    cd "$PROJECT_DIR" || {
        print_error "Cannot access project directory: $PROJECT_DIR"
        exit 1
    }
    
    # Ensure we're in a git repository
    if [[ ! -d ".git" ]]; then
        print_error "Not a git repository. Please ensure the project is managed with git."
        exit 1
    fi
    
    # Create necessary directories
    mkdir -p "$BACKUP_DIR"
    mkdir -p "$(dirname "$LOG_FILE")"
    
    # Set trap for cleanup on failure
    trap rollback ERR
    
    # Step 1: Create backup
    create_backup
    
    # Step 2: Pull latest changes
    print_step "Pulling latest changes from repository..."
    git fetch origin || {
        print_error "Failed to fetch from origin"
        exit 1
    }
    
    local current_branch=$(git branch --show-current)
    git pull origin "$current_branch" || {
        print_error "Failed to pull changes"
        exit 1
    }
    
    # Step 3: Analyze changes
    analyze_changes
    
    # Step 4: Enable maintenance mode
    enable_maintenance
    
    # Step 5: Update dependencies and build assets
    update_composer
    build_frontend
    
    # Step 6: Run migrations
    run_migrations
    
    # Step 7: Update caches
    update_caches
    
    # Step 8: Update storage links
    update_storage
    
    # Step 9: Set permissions
    set_permissions
    
    # Step 10: Restart services
    restart_queues
    
    # Step 11: Disable maintenance mode
    disable_maintenance
    
    # Step 12: Run health checks
    run_health_checks
    
    # Calculate execution time
    local end_time=$(date +%s)
    local execution_time=$((end_time - start_time))
    
    print_status "=========================================="
    print_status "TimeKeeper Production Update Completed"
    print_status "Execution time: ${execution_time} seconds"
    print_status "Timestamp: $(date)"
    print_status "=========================================="
    
    # Send success notification
    send_notification "SUCCESS" "TimeKeeper production update completed successfully in ${execution_time} seconds"
    
    # Clean up old backups (keep last 10)
    find "$BACKUP_DIR" -maxdepth 1 -type d -name "pre-update-*" | sort -r | tail -n +11 | xargs rm -rf 2>/dev/null || true
}

# Script usage information
usage() {
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -h, --help     Show this help message"
    echo "  -f, --force    Force update even if no changes detected"
    echo "  -n, --no-backup Skip backup creation"
    echo "  -d, --dry-run  Show what would be updated without making changes"
    echo ""
    echo "Examples:"
    echo "  $0                 # Normal update with change detection"
    echo "  $0 --force         # Force full update"
    echo "  $0 --dry-run       # Preview changes without updating"
}

# Parse command line arguments
FORCE_UPDATE=false
SKIP_BACKUP=false
DRY_RUN=false

while [[ $# -gt 0 ]]; do
    case $1 in
        -h|--help)
            usage
            exit 0
            ;;
        -f|--force)
            FORCE_UPDATE=true
            shift
            ;;
        -n|--no-backup)
            SKIP_BACKUP=true
            shift
            ;;
        -d|--dry-run)
            DRY_RUN=true
            shift
            ;;
        *)
            print_error "Unknown option: $1"
            usage
            exit 1
            ;;
    esac
done

# Execute main function if not dry run
if [[ "$DRY_RUN" == true ]]; then
    print_status "DRY RUN MODE - No changes will be made"
    cd "$PROJECT_DIR" || exit 1
    analyze_changes
    print_status "Dry run completed. Use without --dry-run to perform actual update."
else
    main
fi