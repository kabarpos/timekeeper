# 🚀 TimeKeeper Deployment Scripts

Kumpulan script untuk deployment dan maintenance production TimeKeeper dengan mudah dan aman.

## 📋 Daftar Script

### 1. `deploy.sh` - Full Deployment Script
Script lengkap untuk deployment production dengan backup otomatis dan rollback.

**Fitur:**
- ✅ Backup otomatis sebelum update
- ✅ Pull latest changes dari repository
- ✅ Update Composer dependencies
- ✅ Build NPM assets
- ✅ Run database migrations
- ✅ Clear dan optimize caches
- ✅ Set permissions yang tepat
- ✅ Auto rollback jika terjadi error
- ✅ Cleanup backup lama (keep 5 terakhir)

**Penggunaan:**
```bash
chmod +x deploy.sh
./deploy.sh
```

### 2. `quick-update.sh` - Quick Update Script
Script cepat untuk update kecil tanpa backup lengkap.

**Fitur:**
- ⚡ Update cepat tanpa backup lengkap
- ✅ Smart detection perubahan dependencies
- ✅ Conditional updates (hanya jika diperlukan)
- ✅ Cache clearing dan optimization

**Penggunaan:**
```bash
chmod +x quick-update.sh
./quick-update.sh
```

### 3. `rollback.sh` - Rollback Script
Script untuk rollback ke backup sebelumnya jika terjadi masalah.

**Fitur:**
- 🔄 Rollback ke backup tertentu atau terbaru
- ✅ List backup yang tersedia
- ✅ Backup state saat ini sebelum rollback
- ✅ Restore dependencies dan rebuild assets

**Penggunaan:**
```bash
chmod +x rollback.sh

# Rollback ke backup terbaru
./rollback.sh

# Rollback ke backup tertentu
./rollback.sh backup_20250121_143022
```

## 🛠️ Setup Awal

### 1. Persiapan Server
```bash
# Clone repository
git clone <repository-url> /path/to/project
cd /path/to/project

# Set permissions untuk script
chmod +x deploy.sh quick-update.sh rollback.sh

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate
php artisan migrate --force
```

### 2. Konfigurasi Environment
Pastikan file `.env` sudah dikonfigurasi dengan benar:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=timekeeper
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 📖 Panduan Penggunaan

### Deployment Rutin (Recommended)
Untuk update production reguler:
```bash
./deploy.sh
```

Script ini akan:
1. Cek repository dan uncommitted changes
2. Buat backup lengkap
3. Pull latest changes
4. Update dependencies jika diperlukan
5. Run migrations baru
6. Optimize application
7. Set permissions
8. Cleanup backup lama

### Quick Update
Untuk perubahan kecil atau hotfix:
```bash
./quick-update.sh
```

Script ini lebih cepat karena:
- Tidak membuat backup lengkap
- Hanya update dependencies jika berubah
- Hanya run migrations jika ada yang baru

### Emergency Rollback
Jika terjadi masalah setelah deployment:
```bash
# Lihat backup yang tersedia
ls -la backups/

# Rollback ke backup terbaru
./rollback.sh

# Atau rollback ke backup tertentu
./rollback.sh backup_20250121_143022
```

## 🔧 Kustomisasi

### Mengubah Direktori Backup
Edit variabel `BACKUP_DIR` di script:
```bash
BACKUP_DIR="/path/to/custom/backup/directory"
```

### Menambah File/Direktori ke Backup
Edit fungsi `backup_current_state()` di `deploy.sh`:
```bash
cp -r custom_directory "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
cp custom_file.conf "$BACKUP_DIR/$BACKUP_NAME/" 2>/dev/null || true
```

### Skip Langkah Tertentu
Tambahkan flag environment variable:
```bash
# Skip NPM build
SKIP_NPM=1 ./deploy.sh

# Skip migrations
SKIP_MIGRATIONS=1 ./deploy.sh
```

## 🚨 Troubleshooting

### Permission Denied
```bash
chmod +x deploy.sh quick-update.sh rollback.sh
```

### Git Not Found
```bash
# Install git
sudo apt-get install git  # Ubuntu/Debian
sudo yum install git       # CentOS/RHEL
```

### Composer Not Found
```bash
# Install composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Node/NPM Not Found
```bash
# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### Database Connection Error
1. Cek konfigurasi `.env`
2. Pastikan database server running
3. Cek credentials database

### Rollback Gagal
Jika rollback gagal, restore manual:
```bash
# Lihat backup yang tersedia
ls -la backups/

# Copy manual dari backup
cp -r backups/backup_YYYYMMDD_HHMMSS/* .
```

## 📊 Monitoring

### Log Deployment
Script akan menampilkan log real-time. Untuk menyimpan log:
```bash
./deploy.sh 2>&1 | tee deployment.log
```

### Cek Status Setelah Deployment
```bash
# Cek aplikasi
php artisan about

# Cek database
php artisan migrate:status

# Cek cache
php artisan cache:table
```

## 🔐 Security Best Practices

1. **Jangan commit script ke repository public** jika berisi credentials
2. **Set proper file permissions:**
   ```bash
   chmod 700 deploy.sh quick-update.sh rollback.sh
   ```
3. **Gunakan SSH keys** untuk git authentication
4. **Backup database** secara terpisah
5. **Monitor logs** untuk aktivitas mencurigakan

## 📞 Support

Jika mengalami masalah:
1. Cek log error di `storage/logs/laravel.log`
2. Cek web server error logs
3. Gunakan rollback jika diperlukan
4. Contact system administrator

---

**Happy Deploying! 🎉**