@echo off
REM ===========================================
REM TIMEKEEPER PRODUCTION DEPLOYMENT SCRIPT
REM ===========================================

echo.
echo ========================================
echo   TimeKeeper Production Deployment
echo ========================================
echo.

REM Colors (Windows doesn't support colors in batch easily, so we use simple text)
echo [INFO] Memulai deployment production...

REM 1. Check if .env.production exists
if not exist ".env.production" (
    echo [ERROR] File .env.production tidak ditemukan!
    echo [ERROR] Silakan buat file .env.production terlebih dahulu
    pause
    exit /b 1
)

REM 2. Backup current .env
echo [INFO] 1. Backup file .env saat ini...
if exist ".env" (
    for /f "tokens=2-4 delims=/ " %%a in ('date /t') do (set mydate=%%c%%a%%b)
    for /f "tokens=1-2 delims=/:" %%a in ('time /t') do (set mytime=%%a%%b)
    copy ".env" ".env.backup.%mydate%_%mytime%"
    echo [SUCCESS] Backup .env berhasil dibuat
) else (
    echo [WARNING] File .env tidak ditemukan, skip backup
)

REM 3. Switch to production environment
echo [INFO] 2. Switching ke production environment...
copy ".env.production" ".env"
echo [SUCCESS] Environment switched ke production

REM 4. Clear config cache
echo [INFO] 3. Clearing config cache...
php artisan config:clear
php artisan cache:clear
echo [SUCCESS] Cache cleared

REM 5. Check database connection
echo [INFO] 4. Testing koneksi database...
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection: OK'; } catch(Exception $e) { echo 'Database connection: FAILED - ' . $e->getMessage(); exit(1); }"
if %errorlevel% neq 0 (
    echo [ERROR] Koneksi database gagal! Periksa konfigurasi database di .env.production
    pause
    exit /b 1
)
echo [SUCCESS] Koneksi database berhasil

REM 6. Check migration status
echo [INFO] 5. Checking migration status...
php artisan migrate:status

REM 7. Ask for migration strategy
echo.
echo Pilih strategi migration:
echo 1. Fresh Migration (HAPUS SEMUA DATA - HATI-HATI!)
echo 2. Step by Step Migration (Aman)
echo 3. Skip Migration
echo.
set /p choice="Pilih (1/2/3): "

if "%choice%"=="1" (
    echo.
    echo [WARNING] PERINGATAN: Ini akan menghapus SEMUA DATA!
    set /p confirm="Apakah Anda yakin? Ketik 'YES' untuk konfirmasi: "
    if "!confirm!"=="YES" (
        echo [WARNING] Melakukan migration:fresh...
        php artisan migrate:fresh --force
        echo [SUCCESS] Migration fresh completed
    ) else (
        echo [INFO] Migration fresh dibatalkan
        goto :step_migration
    )
) else if "%choice%"=="2" (
    :step_migration
    echo [INFO] 6. Menjalankan migration step by step...
    
    REM Run base Laravel migrations first
    echo [INFO] Running base Laravel migrations...
    php artisan migrate --path=database/migrations/0001_01_01_000000_create_users_table.php --force
    php artisan migrate --path=database/migrations/0001_01_01_000001_create_cache_table.php --force
    php artisan migrate --path=database/migrations/0001_01_01_000002_create_jobs_table.php --force
    
    REM Run TimeKeeper core migrations
    echo [INFO] Running TimeKeeper core migrations...
    php artisan migrate --path=database/migrations/2025_09_17_135351_create_timers_table.php --force
    php artisan migrate --path=database/migrations/2025_09_17_135357_create_messages_table.php --force
    php artisan migrate --path=database/migrations/2025_09_17_135403_create_settings_table.php --force
    
    REM Run additional migrations
    echo [INFO] Running additional migrations...
    php artisan migrate --path=database/migrations/2025_09_17_182251_add_timer_colors_to_settings_table.php --force
    php artisan migrate --path=database/migrations/2025_09_19_111206_drop_type_column_from_messages_table.php --force
    php artisan migrate --path=database/migrations/2025_09_19_131731_add_role_to_users_table.php --force
    
    REM Finally run the index migration (with corrected timestamp)
    echo [INFO] Running index migration...
    php artisan migrate --path=database/migrations/2025_09_19_200000_add_database_indexes.php --force
    
    echo [SUCCESS] Step by step migration completed
) else (
    echo [INFO] Migration dilewati
)

REM 8. Verify tables exist
echo [INFO] 7. Verifying tables...
php artisan tinker --execute="try { $tables = ['users', 'timers', 'messages', 'settings']; foreach($tables as $table) { if(Schema::hasTable($table)) { echo '✓ Table ' . $table . ' exists' . PHP_EOL; } else { echo '✗ Table ' . $table . ' missing' . PHP_EOL; } } } catch(Exception $e) { echo 'Error checking tables: ' . $e->getMessage() . PHP_EOL; }"

REM 9. Ask for seeders
echo.
set /p seed_choice="Apakah Anda ingin menjalankan seeders? (y/N): "
if /i "%seed_choice%"=="y" (
    echo [INFO] 8. Running seeders...
    php artisan db:seed --force
    echo [SUCCESS] Seeders completed
)

REM 10. Optimize for production
echo [INFO] 9. Optimizing untuk production...
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
echo [SUCCESS] Production optimization completed

REM 11. Final migration status
echo [INFO] 10. Final migration status:
php artisan migrate:status

echo.
echo [SUCCESS] 🎉 Production deployment completed!
echo.
echo [WARNING] Jangan lupa untuk:
echo    - Backup database secara berkala
echo    - Monitor log aplikasi
echo    - Test semua fitur aplikasi
echo    - Update DNS jika diperlukan
echo.

REM 12. Ask to restore development environment
set /p restore_choice="Apakah Anda ingin kembali ke development environment? (y/N): "
if /i "%restore_choice%"=="y" (
    REM Find latest backup
    for /f %%i in ('dir /b /o-d .env.backup.* 2^>nul') do (
        copy "%%i" ".env"
        php artisan config:clear
        echo [SUCCESS] Kembali ke development environment
        goto :restored
    )
    echo [WARNING] Backup .env tidak ditemukan, silakan restore manual
    :restored
)

echo.
echo Script selesai!
pause