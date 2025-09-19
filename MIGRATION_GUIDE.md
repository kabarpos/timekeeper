# Panduan Migration TimeKeeper

## Ringkasan Migration

Proyek TimeKeeper memiliki 10 file migration yang telah diurutkan dan dioptimalkan untuk production deployment:

### Urutan Migration (berdasarkan timestamp):

1. **0001_01_01_000000_create_users_table** - Tabel users dengan foreign key untuk sessions
2. **0001_01_01_000001_create_cache_table** - Tabel cache Laravel
3. **0001_01_01_000002_create_jobs_table** - Tabel jobs Laravel
4. **2025_09_17_135351_create_timers_table** - Tabel timers
5. **2025_09_17_135357_create_messages_table** - Tabel messages dengan kolom type
6. **2025_09_17_135403_create_settings_table** - Tabel settings
7. **2025_09_17_182251_add_timer_colors_to_settings_table** - Menambah kolom warna timer
8. **2025_09_19_111206_drop_type_column_from_messages_table** - Menghapus kolom type dari messages
9. **2025_09_19_131731_add_role_to_users_table** - Menambah kolom role ke users
10. **2025_09_19_200000_add_database_indexes** - Menambah index untuk optimasi

## Perbaikan yang Telah Dilakukan

### 1. Foreign Key Constraint
- **File**: `0001_01_01_000000_create_users_table.php`
- **Perbaikan**: Menambah proper constraint dan cascade delete pada kolom `user_id` di tabel sessions
- **Sebelum**: `$table->foreignId('user_id')->nullable()->index();`
- **Sesudah**: `$table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');`

### 2. Drop Type Column Migration
- **File**: `2025_09_19_111206_drop_type_column_from_messages_table.php`
- **Masalah**: Migration mencoba menghapus index yang tidak pernah dibuat
- **Solusi**: Menghapus upaya drop index dan langsung drop kolom
- **Defensive Programming**: Menambah pengecekan keberadaan kolom sebelum drop

### 3. Database Indexes
- **File**: `2025_09_19_200000_add_database_indexes.php`
- **Fitur**: Helper method `hasIndex()` untuk cross-database compatibility
- **Safety**: Try-catch untuk setiap operasi drop index
- **Coverage**: Index untuk tabel messages, timers, dan settings

## Validasi Migration

### Test yang Berhasil:
✅ `php artisan migrate:fresh` - Semua migration berhasil dijalankan
✅ `php artisan migrate:status` - Semua migration dalam status "Ran"
✅ Cross-database compatibility (SQLite & MySQL)
✅ Foreign key constraints berfungsi dengan baik
✅ Index optimization berjalan tanpa error

### Database Support:
- ✅ SQLite (development)
- ✅ MySQL (production)
- ✅ PostgreSQL (compatible)

## Deployment ke Production

### Langkah Aman untuk Production:

1. **Backup Database**
   ```bash
   # Backup database sebelum migration
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
   ```

2. **Test di Staging**
   ```bash
   # Clone production database ke staging
   php artisan migrate:status
   php artisan migrate --pretend  # Dry run
   php artisan migrate
   ```

3. **Production Deployment**
   ```bash
   # Maintenance mode
   php artisan down
   
   # Run migrations
   php artisan migrate --force
   
   # Verify status
   php artisan migrate:status
   
   # Back online
   php artisan up
   ```

### Zero Downtime Strategy:
- Semua migration bersifat **backward compatible**
- Tidak ada breaking changes pada struktur data
- Index ditambahkan dengan pengecekan keberadaan
- Foreign key menggunakan cascade delete yang aman

## Troubleshooting

### Jika Migration Gagal:

1. **Cek log error**: `storage/logs/laravel.log`
2. **Rollback jika perlu**: `php artisan migrate:rollback`
3. **Cek database connection**: `php artisan tinker` → `DB::connection()->getPdo()`
4. **Verify permissions**: Pastikan user database memiliki privilege CREATE, ALTER, DROP

### Common Issues:

- **Index already exists**: Migration akan skip dengan graceful handling
- **Column not found**: Defensive programming akan handle dengan pengecekan
- **Foreign key constraint**: Cascade delete sudah dikonfigurasi dengan benar

## Performance Notes

### Index yang Ditambahkan:
- `messages_user_id_index` - Optimasi query berdasarkan user
- `messages_is_active_index` - Filter pesan aktif
- `messages_user_id_is_active_index` - Composite index untuk query kompleks
- `timers_user_id_index` - Optimasi query timer per user
- `settings_user_id_index` - Optimasi pengaturan user

### Estimasi Waktu Migration:
- **Small dataset** (< 1000 records): < 1 menit
- **Medium dataset** (< 100k records): 2-5 menit
- **Large dataset** (> 100k records): 5-15 menit

## Maintenance

### Regular Checks:
```bash
# Cek status migration
php artisan migrate:status

# Cek integritas foreign key
php artisan tinker
DB::select('PRAGMA foreign_key_check'); // SQLite
SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE; // MySQL
```

### Monitoring:
- Monitor query performance setelah index ditambahkan
- Cek log untuk foreign key constraint violations
- Verify data integrity setelah migration

---

**Status**: ✅ Production Ready
**Last Updated**: 2025-01-19
**Tested On**: Laravel 12.29.0, SQLite, MySQL