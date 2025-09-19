# 🚀 TimeKeeper Production Update Guide

Panduan lengkap untuk menggunakan script update otomatis TimeKeeper yang menganalisis perubahan file dan menjalankan update yang diperlukan.

## 📋 Daftar Isi

1. [Overview](#overview)
2. [File yang Tersedia](#file-yang-tersedia)
3. [Persiapan](#persiapan)
4. [Cara Penggunaan](#cara-penggunaan)
5. [Fitur Analisis Otomatis](#fitur-analisis-otomatis)
6. [Monitoring dan Logging](#monitoring-dan-logging)
7. [Troubleshooting](#troubleshooting)
8. [Best Practices](#best-practices)

## 🎯 Overview

Script update production TimeKeeper dirancang untuk:
- **Menganalisis perubahan file** yang di-pull dari repository
- **Menentukan update yang diperlukan** berdasarkan file yang berubah
- **Menjalankan update secara otomatis** dengan backup dan rollback
- **Meminimalkan downtime** dengan maintenance mode yang cerdas
- **Memberikan logging lengkap** untuk monitoring dan debugging

## 📁 File yang Tersedia

### 1. `production-update.sh` (Linux/Unix)
Script utama untuk server Linux/Unix dengan fitur lengkap:
- Analisis perubahan file yang canggih
- Backup otomatis dengan timestamp
- Rollback otomatis jika terjadi error
- Health checks setelah update
- Notifikasi (dapat dikustomisasi)

### 2. `production-update.bat` (Windows)
Versi Windows dari script update dengan fitur:
- Kompatibel dengan Windows Command Prompt
- Analisis perubahan file dasar
- Backup dan rollback sederhana
- Logging ke file

### 3. `update-guide.md` (Panduan ini)
Dokumentasi lengkap penggunaan script update.

## ⚙️ Persiapan

### Persyaratan Sistem

**Linux/Unix:**
```bash
# Pastikan tools berikut terinstall:
- git
- php (8.1+)
- composer
- npm/node.js
- mysql/mariadb
- redis (opsional)
- supervisor (untuk queue workers)
```

**Windows:**
```cmd
# Pastikan tools berikut terinstall dan ada di PATH:
- git
- php (8.1+)
- composer
- npm/node.js
- mysql/mariadb
```

### Setup Awal

1. **Copy script ke server production:**
   ```bash
   # Linux
   scp production-update.sh user@server:/var/www/timekeeper/
   chmod +x /var/www/timekeeper/production-update.sh
   
   # Windows
   # Copy production-update.bat ke folder project
   ```

2. **Konfigurasi path di script:**
   ```bash
   # Edit bagian configuration di script
   PROJECT_DIR="/var/www/timekeeper"  # Sesuaikan dengan path project
   BACKUP_DIR="/backup/timekeeper/updates"  # Path untuk backup
   ```

3. **Setup permissions (Linux):**
   ```bash
   chown www-data:www-data /var/www/timekeeper/production-update.sh
   chmod +x /var/www/timekeeper/production-update.sh
   ```

## 🚀 Cara Penggunaan

### Linux/Unix

**Update Normal (dengan analisis perubahan):**
```bash
cd /var/www/timekeeper
./production-update.sh
```

**Update dengan opsi:**
```bash
# Force update (tanpa analisis perubahan)
./production-update.sh --force

# Dry run (preview tanpa update)
./production-update.sh --dry-run

# Skip backup
./production-update.sh --no-backup

# Lihat help
./production-update.sh --help
```

### Windows

**Update Normal:**
```cmd
cd C:\path\to\timekeeper
production-update.bat
```

### Melalui Cron Job (Linux)

**Setup cron untuk update otomatis:**
```bash
# Edit crontab
crontab -e

# Tambahkan line berikut untuk update setiap hari jam 2 pagi
0 2 * * * /var/www/timekeeper/production-update.sh >> /var/log/timekeeper-update.log 2>&1

# Atau untuk update setiap 6 jam
0 */6 * * * /var/www/timekeeper/production-update.sh >> /var/log/timekeeper-update.log 2>&1
```

## 🔍 Fitur Analisis Otomatis

Script akan menganalisis file yang berubah dan menentukan update yang diperlukan:

### Deteksi Perubahan File

| File yang Berubah | Update yang Dijalankan |
|-------------------|------------------------|
| `composer.json`, `composer.lock` | ✅ Composer install + optimize |
| `package.json`, `package-lock.json` | ✅ NPM install + build |
| `database/migrations/*` | ✅ Database migration |
| `config/*` | ✅ Config cache rebuild |
| `routes/*` | ✅ Route cache rebuild |
| `resources/views/*` | ✅ View cache rebuild |
| `resources/css/*`, `resources/js/*` | ✅ Frontend build |
| `app/*` | ✅ Cache clear + queue restart |
| `.env.example`, `storage/*` | ✅ Storage link update |

### Contoh Output Analisis

```
[ANALYSIS] Changed files detected:
  - composer.json
  - resources/js/app.js
  - database/migrations/2024_01_15_create_new_table.php

[ANALYSIS] Update requirements summary:
  ✓ Composer update required
  ✓ NPM build required
  ✓ Database migration required
  ✓ Cache clearing required
```

## 📊 Monitoring dan Logging

### Log Files

**Linux:**
- Main log: `storage/logs/production-update.log`
- System log: `/var/log/timekeeper-update.log` (jika menggunakan cron)

**Windows:**
- Main log: `storage\logs\production-update.log`

### Format Log

```
[2024-01-15 14:30:15] [INFO] TimeKeeper Production Update Started
[2024-01-15 14:30:16] [ANALYSIS] Previous commit: abc123...
[2024-01-15 14:30:16] [ANALYSIS] Current commit: def456...
[2024-01-15 14:30:17] [ANALYSIS] Changed files detected:
[2024-01-15 14:30:17] [ANALYSIS]   - composer.json
[2024-01-15 14:30:18] [STEP] Creating backup...
[2024-01-15 14:30:20] [STEP] Updating Composer dependencies...
[2024-01-15 14:30:45] [INFO] TimeKeeper Production Update Completed
```

### Health Checks

Script akan menjalankan health checks setelah update:
- ✅ Application response check
- ✅ Database connection check
- ✅ Redis connection check (jika tersedia)
- ✅ Queue workers status check

## 🛠️ Troubleshooting

### Error Umum dan Solusi

**1. Permission Denied**
```bash
# Solusi Linux:
sudo chown -R www-data:www-data /var/www/timekeeper
sudo chmod +x /var/www/timekeeper/production-update.sh

# Solusi Windows:
# Jalankan Command Prompt sebagai Administrator
```

**2. Git Pull Failed**
```bash
# Check git status
git status
git stash  # Jika ada uncommitted changes
git pull origin main
```

**3. Composer Install Failed**
```bash
# Clear composer cache
composer clear-cache
composer install --no-dev --optimize-autoloader
```

**4. NPM Build Failed**
```bash
# Clear npm cache
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
npm run build
```

**5. Database Migration Failed**
```bash
# Check migration status
php artisan migrate:status

# Rollback last migration if needed
php artisan migrate:rollback --step=1

# Re-run migration
php artisan migrate --force
```

### Rollback Manual

Jika automatic rollback gagal:

```bash
# Linux
BACKUP_PATH="/backup/timekeeper/updates/pre-update-YYYYMMDD_HHMMSS"
cp -r $BACKUP_PATH/* /var/www/timekeeper/

# Windows
set BACKUP_PATH=backup\updates\pre-update-YYYYMMDD_HHMMSS
xcopy %BACKUP_PATH%\* . /E /I /Y

# Clear caches setelah rollback
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📋 Best Practices

### 1. Testing di Staging

Selalu test script di staging environment terlebih dahulu:
```bash
# Setup staging environment
cp production-update.sh staging-update.sh
# Edit PROJECT_DIR ke staging path
./staging-update.sh --dry-run
```

### 2. Backup Strategy

- Script otomatis membuat backup sebelum update
- Backup disimpan dengan timestamp
- Otomatis menghapus backup lama (keep 10 terakhir)
- Untuk backup manual tambahan:

```bash
# Manual backup sebelum update besar
tar -czf timekeeper-backup-$(date +%Y%m%d).tar.gz /var/www/timekeeper
mysqldump -u user -p database_name > timekeeper-db-$(date +%Y%m%d).sql
```

### 3. Monitoring

Setup monitoring untuk memastikan update berjalan lancar:

```bash
# Setup log rotation
sudo nano /etc/logrotate.d/timekeeper-update

# Content:
/var/www/timekeeper/storage/logs/production-update.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
    create 644 www-data www-data
}
```

### 4. Notification Setup

Customize notification di script untuk mendapat alert:

```bash
# Edit function send_notification() di script
send_notification() {
    local status="$1"
    local message="$2"
    
    # Slack notification
    curl -X POST -H 'Content-type: application/json' \
        --data '{"text":"TimeKeeper Update: '$message'"}' \
        YOUR_SLACK_WEBHOOK_URL
    
    # Email notification
    echo "$message" | mail -s "TimeKeeper Update: $status" admin@yourdomain.com
}
```

### 5. Security

- Jalankan script dengan user yang tepat (www-data)
- Pastikan file .env tidak ter-backup ke tempat yang tidak aman
- Set proper permissions untuk backup directory
- Gunakan SSH key untuk git operations

### 6. Performance

- Jalankan update di jam dengan traffic rendah
- Monitor resource usage selama update
- Gunakan maintenance mode untuk menghindari error user
- Pertimbangkan blue-green deployment untuk aplikasi critical

## 🎯 Kesimpulan

Script update production TimeKeeper memberikan:
- ✅ **Otomatisasi penuh** proses update production
- ✅ **Analisis cerdas** perubahan file
- ✅ **Backup dan rollback** otomatis
- ✅ **Logging lengkap** untuk monitoring
- ✅ **Minimal downtime** dengan maintenance mode
- ✅ **Health checks** setelah update

Dengan menggunakan script ini, proses deployment menjadi lebih aman, cepat, dan dapat diandalkan.

---

**📞 Support:**
Jika mengalami masalah, check log file dan ikuti troubleshooting guide di atas. Untuk masalah yang kompleks, backup selalu tersedia untuk rollback manual.