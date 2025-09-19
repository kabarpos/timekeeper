# Migration Fix Guide - TimeKeeper

## Masalah yang Terjadi

Error yang terjadi:
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'timekeeper.messages' doesn't exist
```

## Analisis Root Cause

1. **Migration Order Issue**: Migration `2025_01_20_000001_add_database_indexes.php` mencoba menambahkan index pada kolom `type` di tabel `messages`
2. **Column Dependency**: Migration `2025_09_19_111206_drop_type_column_from_messages_table.php` menghapus kolom `type` dari tabel `messages`
3. **Batch Conflict**: Kedua migration berada di batch yang berbeda, menyebabkan konflik saat rollback/migrate

## Solusi yang Diterapkan

### 1. Perbaikan Migration `add_database_indexes.php`

**Perubahan Utama:**
- ✅ Menghapus referensi ke kolom `type` yang sudah tidak ada
- ✅ Menambahkan pengecekan index sebelum membuat/menghapus
- ✅ Implementasi try-catch untuk error handling
- ✅ Kompatibilitas dengan SQLite dan MySQL

**Kode Sebelum:**
```php
// Index untuk query type
$table->index('type');
```

**Kode Sesudah:**
```php
// Kolom type sudah dihapus, tidak perlu index
// Fokus pada index yang masih relevan
```

### 2. Helper Method untuk Index Management

```php
private function hasIndex(string $table, string $indexName): bool
{
    try {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        // For MySQL
        if ($connection->getDriverName() === 'mysql') {
            $result = $connection->select("
                SELECT COUNT(*) as count 
                FROM information_schema.statistics 
                WHERE table_schema = ? AND table_name = ? AND index_name = ?
            ", [$databaseName, $table, $indexName]);
            
            return $result[0]->count > 0;
        }
        
        // For SQLite
        if ($connection->getDriverName() === 'sqlite') {
            $result = $connection->select("
                SELECT name FROM sqlite_master 
                WHERE type='index' AND name = ?
            ", [$indexName]);
            
            return count($result) > 0;
        }
        
        return false;
    } catch (\Exception $e) {
        return false;
    }
}
```

### 3. Safe Index Dropping

```php
public function down(): void
{
    Schema::table('messages', function (Blueprint $table) {
        try {
            $table->dropIndex('messages_is_active_index');
        } catch (\Exception $e) {
            // Index doesn't exist, continue
        }
        // ... other indexes
    });
}
```

## Langkah Perbaikan yang Dilakukan

1. **Rollback Migration Bermasalah**
   ```bash
   php artisan migrate:rollback --step=1
   ```

2. **Perbaiki Migration File**
   - Hapus referensi kolom `type`
   - Tambahkan pengecekan index
   - Implementasi error handling

3. **Re-run Migration**
   ```bash
   php artisan migrate
   ```

## Hasil Akhir

✅ Semua migration berhasil dijalankan
✅ Index database optimal untuk performa
✅ Tidak ada konflik antar migration
✅ Kompatibilitas dengan SQLite dan MySQL

## Best Practices untuk Migration

### 1. Dependency Management
- Selalu cek dependensi antar migration
- Hindari referensi ke kolom yang akan dihapus
- Gunakan migration order yang logis

### 2. Index Management
- Cek keberadaan index sebelum membuat/menghapus
- Gunakan try-catch untuk error handling
- Buat index name yang konsisten

### 3. Database Compatibility
- Test migration di environment yang berbeda
- Implementasi fallback untuk driver database berbeda
- Gunakan Laravel Schema Builder untuk portability

### 4. Error Handling
```php
// Good Practice
try {
    $table->dropIndex('index_name');
} catch (\Exception $e) {
    // Log error but continue
    \Log::warning("Index doesn't exist: " . $e->getMessage());
}

// Bad Practice
$table->dropIndex('index_name'); // Bisa error jika index tidak ada
```

## Monitoring & Maintenance

### Cek Status Migration
```bash
php artisan migrate:status
```

### Rollback Aman
```bash
php artisan migrate:rollback --step=1
```

### Fresh Migration (Development Only)
```bash
php artisan migrate:fresh --seed
```

## Kesimpulan

Masalah migration telah berhasil diperbaiki dengan:
1. Menghapus referensi ke kolom yang sudah tidak ada
2. Implementasi pengecekan index yang aman
3. Error handling yang robust
4. Kompatibilitas multi-database

Migration sekarang berjalan dengan lancar dan database memiliki index yang optimal untuk performa aplikasi TimeKeeper.