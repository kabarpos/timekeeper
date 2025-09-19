#!/bin/bash

# ===========================================
# PRODUCTION MIGRATION FIX SCRIPT
# ===========================================
# Script untuk memperbaiki masalah migration di production MySQL

echo "🔧 TimeKeeper Production Migration Fix"
echo "======================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if .env.production exists
if [ ! -f ".env.production" ]; then
    print_error ".env.production file tidak ditemukan!"
    exit 1
fi

print_status "Memulai perbaikan migration production..."

# 1. Backup current .env
print_status "1. Backup file .env saat ini..."
if [ -f ".env" ]; then
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
    print_success "Backup .env berhasil dibuat"
fi

# 2. Switch to production environment
print_status "2. Switching ke production environment..."
cp .env.production .env
print_success "Environment switched ke production"

# 3. Clear config cache
print_status "3. Clearing config cache..."
php artisan config:clear
php artisan cache:clear
print_success "Cache cleared"

# 4. Check database connection
print_status "4. Testing koneksi database..."
if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection: OK'; } catch(Exception \$e) { echo 'Database connection: FAILED - ' . \$e->getMessage(); exit(1); }"; then
    print_success "Koneksi database berhasil"
else
    print_error "Koneksi database gagal! Periksa konfigurasi database di .env.production"
    exit 1
fi

# 5. Check migration status
print_status "5. Checking migration status..."
php artisan migrate:status

# 6. Reset migrations (HATI-HATI: Ini akan menghapus semua data!)
read -p "⚠️  PERINGATAN: Apakah Anda ingin reset semua migration? Ini akan menghapus semua data! (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_warning "Melakukan migration:fresh..."
    php artisan migrate:fresh --force
    print_success "Migration fresh completed"
else
    # 7. Alternative: Run migrations step by step
    print_status "6. Menjalankan migration step by step..."
    
    # Run base Laravel migrations first
    print_status "Running base Laravel migrations..."
    php artisan migrate --path=database/migrations/0001_01_01_000000_create_users_table.php --force
    php artisan migrate --path=database/migrations/0001_01_01_000001_create_cache_table.php --force
    php artisan migrate --path=database/migrations/0001_01_01_000002_create_jobs_table.php --force
    
    # Run TimeKeeper core migrations
    print_status "Running TimeKeeper core migrations..."
    php artisan migrate --path=database/migrations/2025_09_17_135351_create_timers_table.php --force
    php artisan migrate --path=database/migrations/2025_09_17_135357_create_messages_table.php --force
    php artisan migrate --path=database/migrations/2025_09_17_135403_create_settings_table.php --force
    
    # Run additional migrations
    print_status "Running additional migrations..."
    php artisan migrate --path=database/migrations/2025_09_17_182251_add_timer_colors_to_settings_table.php --force
    
    # Skip problematic migrations for now
    print_warning "Skipping problematic migrations temporarily..."
    
    # Run remaining migrations
    php artisan migrate --path=database/migrations/2025_09_19_111206_drop_type_column_from_messages_table.php --force
    php artisan migrate --path=database/migrations/2025_09_19_131731_add_role_to_users_table.php --force
    
    # Finally run the index migration
    print_status "Running index migration..."
    php artisan migrate --path=database/migrations/2025_01_20_000001_add_database_indexes.php --force
fi

# 8. Verify tables exist
print_status "7. Verifying tables..."
php artisan tinker --execute="
try {
    \$tables = ['users', 'timers', 'messages', 'settings'];
    foreach(\$tables as \$table) {
        if(Schema::hasTable(\$table)) {
            echo '✓ Table ' . \$table . ' exists' . PHP_EOL;
        } else {
            echo '✗ Table ' . \$table . ' missing' . PHP_EOL;
        }
    }
} catch(Exception \$e) {
    echo 'Error checking tables: ' . \$e->getMessage() . PHP_EOL;
}
"

# 9. Run seeders if needed
read -p "Apakah Anda ingin menjalankan seeders? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_status "8. Running seeders..."
    php artisan db:seed --force
    print_success "Seeders completed"
fi

# 10. Optimize for production
print_status "9. Optimizing untuk production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
print_success "Production optimization completed"

# 11. Final migration status
print_status "10. Final migration status:"
php artisan migrate:status

print_success "🎉 Production migration fix completed!"
print_warning "Jangan lupa untuk:"
echo "   - Backup database secara berkala"
echo "   - Monitor log aplikasi"
echo "   - Test semua fitur aplikasi"
echo "   - Update DNS jika diperlukan"

# Restore development environment if needed
read -p "Apakah Anda ingin kembali ke development environment? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    if [ -f ".env.backup.$(date +%Y%m%d)_"* ]; then
        latest_backup=$(ls -t .env.backup.* | head -n1)
        cp "$latest_backup" .env
        php artisan config:clear
        print_success "Kembali ke development environment"
    else
        print_warning "Backup .env tidak ditemukan, silakan restore manual"
    fi
fi

echo "Script selesai!"