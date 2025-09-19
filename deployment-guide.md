# 🚀 TimeKeeper - Panduan Deployment VPS

## 📋 Analisis Efisiensi Project

### ✅ Kelebihan Project
- **Arsitektur Modern**: Laravel 12 + Livewire 3 + Tailwind 4
- **Caching System**: Implementasi CacheService yang komprehensif
- **Performance Monitoring**: Middleware RequestMetrics untuk tracking
- **Error Handling**: Exception handling yang terstruktur
- **Security**: Implementasi best practices keamanan
- **Optimasi Frontend**: Vite dengan terser minification dan chunk splitting

### 🔧 Area Optimasi yang Sudah Diimplementasi
1. **Database Caching**: CacheService dengan TTL management
2. **Frontend Optimization**: Lazy loading, service worker, performance monitoring
3. **Asset Optimization**: Chunk splitting, cache busting, minification
4. **Request Monitoring**: Metrics collection dan performance tracking
5. **Error Handling**: Structured exception handling dengan logging

## 🛠️ Persiapan Server VPS

### 1. Persyaratan Sistem
```bash
# Ubuntu 22.04 LTS (Recommended)
- PHP 8.2+
- MySQL 8.0+ atau MariaDB 10.6+
- Redis 6.0+
- Nginx 1.18+
- Node.js 18+
- Composer 2.x
```

### 2. Instalasi Dependencies
```bash
# Update sistem
sudo apt update && sudo apt upgrade -y

# Install PHP dan extensions
sudo apt install php8.2-fpm php8.2-mysql php8.2-redis php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath -y

# Install MySQL/MariaDB
sudo apt install mariadb-server -y

# Install Redis
sudo apt install redis-server -y

# Install Nginx
sudo apt install nginx -y

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 3. Konfigurasi Database
```sql
-- Login ke MySQL/MariaDB
sudo mysql -u root -p

-- Buat database dan user
CREATE DATABASE timekeeper_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'timekeeper_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON timekeeper_prod.* TO 'timekeeper_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Konfigurasi Redis
```bash
# Edit konfigurasi Redis
sudo nano /etc/redis/redis.conf

# Tambahkan/ubah:
maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000

# Restart Redis
sudo systemctl restart redis-server
sudo systemctl enable redis-server
```

## 📁 Deployment Steps

### 1. Upload Project
```bash
# Clone atau upload project ke server
cd /var/www/
sudo git clone https://github.com/yourusername/timekeeper.git
sudo chown -R www-data:www-data timekeeper/
cd timekeeper/
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies dan build assets
npm ci
npm run build
```

### 3. Konfigurasi Environment
```bash
# Copy file .env.production
cp .env.production .env

# Generate APP_KEY
php artisan key:generate

# Edit .env dengan data server Anda
nano .env
```

### 4. Setup Database
```bash
# Jalankan migrasi
php artisan migrate --force

# Seed data (opsional)
php artisan db:seed --force
```

### 5. Optimasi Laravel
```bash
# Cache konfigurasi
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Cache events
php artisan event:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 6. Konfigurasi Nginx
```nginx
# /etc/nginx/sites-available/timekeeper
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/timekeeper/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Asset caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 7. SSL Certificate (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Generate SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Tambahkan: 0 12 * * * /usr/bin/certbot renew --quiet
```

### 8. Setup Supervisor untuk Queue
```bash
# Install Supervisor
sudo apt install supervisor -y

# Buat konfigurasi worker
sudo nano /etc/supervisor/conf.d/timekeeper-worker.conf
```

```ini
[program:timekeeper-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/timekeeper/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/timekeeper/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Reload Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start timekeeper-worker:*
```

### 9. Setup Cron Jobs
```bash
# Edit crontab
sudo crontab -e

# Tambahkan Laravel scheduler
* * * * * cd /var/www/timekeeper && php artisan schedule:run >> /dev/null 2>&1
```

### 10. Optimasi PHP-FPM
```bash
# Edit konfigurasi PHP-FPM
sudo nano /etc/php/8.2/fpm/pool.d/www.conf

# Optimasi untuk production:
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 1000

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

### 11. Optimasi PHP (php.ini)
```ini
# /etc/php/8.2/fpm/php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1

memory_limit=512M
max_execution_time=300
upload_max_filesize=50M
post_max_size=50M
```

## 🔍 Monitoring & Maintenance

### 1. Health Check
```bash
# Test aplikasi
curl -I https://yourdomain.com/health

# Monitor logs
tail -f /var/www/timekeeper/storage/logs/laravel.log
```

### 2. Performance Monitoring
- Akses `/admin/monitoring` untuk metrics real-time
- Monitor Redis memory usage: `redis-cli info memory`
- Monitor MySQL performance: `SHOW PROCESSLIST;`

### 3. Backup Strategy
```bash
# Database backup (daily)
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u timekeeper_user -p timekeeper_prod > /backup/db_$DATE.sql
find /backup -name "db_*.sql" -mtime +30 -delete

# Files backup
rsync -av /var/www/timekeeper/storage/ /backup/storage/
rsync -av /var/www/timekeeper/public/uploads/ /backup/uploads/
```

## 🚨 Troubleshooting

### Common Issues
1. **Permission Issues**: `sudo chown -R www-data:www-data /var/www/timekeeper/`
2. **Storage Issues**: `php artisan storage:link`
3. **Cache Issues**: `php artisan cache:clear && php artisan config:clear`
4. **Queue Issues**: `sudo supervisorctl restart timekeeper-worker:*`

### Performance Issues
1. **Slow Queries**: Enable MySQL slow query log
2. **High Memory**: Monitor Redis usage dan PHP memory
3. **High CPU**: Check queue workers dan optimize queries

## 📊 Rekomendasi Optimasi Lanjutan

### 1. CDN Integration
- Gunakan CloudFlare atau AWS CloudFront
- Cache static assets di CDN
- Enable Brotli compression

### 2. Database Optimization
- Implement read replicas untuk scaling
- Regular ANALYZE TABLE untuk query optimization
- Monitor slow queries dan add indexes

### 3. Caching Strategy
- Implement Redis Cluster untuk high availability
- Use Redis Sentinel untuk failover
- Implement application-level caching

### 4. Security Enhancements
- Implement rate limiting
- Use fail2ban untuk brute force protection
- Regular security updates
- Implement WAF (Web Application Firewall)

## ✅ Post-Deployment Checklist

- [ ] SSL certificate installed dan auto-renewal setup
- [ ] Database backup strategy implemented
- [ ] Monitoring tools configured
- [ ] Error logging setup
- [ ] Performance baseline established
- [ ] Security headers configured
- [ ] Queue workers running
- [ ] Cron jobs active
- [ ] Health checks passing
- [ ] Load testing completed

---

**Catatan**: Pastikan untuk mengganti semua placeholder (yourdomain.com, passwords, dll.) dengan nilai yang sesuai untuk server Anda.