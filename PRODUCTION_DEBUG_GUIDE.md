# 🔍 PANDUAN DEBUG PRODUCTION - TimeKeeper

## 🚨 MASALAH YANG TERJADI
Parameter sensitif (email, password) muncul di URL di production, padahal di development normal.

## 🛠️ TOOLS DEBUG YANG SUDAH DIBUAT

### 1. **File Debug HTML**
- **Lokasi**: `public/debug.html`
- **URL Akses**: `https://your-domain.com/debug.html`
- **Fungsi**: Mengecek semua komponen Livewire, CSRF, dan assets

### 2. **Route Debug API**
- **Endpoint**: `POST /debug-form-test`
- **Fungsi**: Testing form submission untuk memastikan POST method bekerja

## 🔧 LANGKAH DEBUGGING PRODUCTION

### STEP 1: Akses Debug Tool
```
1. Buka browser dan akses: https://your-domain.com/debug.html
2. Tunggu hingga semua check selesai (sekitar 2-3 detik)
3. Perhatikan hasil setiap section
```

### STEP 2: Analisis Hasil Debug

#### ✅ **Yang Harus HIJAU (Success):**
- CSRF Meta Tag: ✓ Found
- CSRF Token Value: [token string]
- Livewire Object: ✓ Available
- Vite Assets: ✓ Found
- Build Manifest: ✓ Available

#### ❌ **Yang Menunjukkan MASALAH:**
- Livewire Object: ✗ Not Available
- CSRF Meta Tag: ✗ Not Found
- Vite Assets: ✗ Not Found

### STEP 3: Test Form Submission
```
1. Di section "Form Submission Test"
2. Isi email dan password dummy
3. Klik "Test Form Submission"
4. Lihat hasilnya:
   - ✓ Success = POST method bekerja
   - ✗ Error = Ada masalah dengan form handling
```

### STEP 4: Cek Console Logs
```
1. Scroll ke section "Console Logs"
2. Cari error messages seperti:
   - "Livewire not found"
   - "CSRF token not found"
   - "Failed to load resource"
   - Network errors
```

## 🎯 DIAGNOSIS BERDASARKAN HASIL

### SKENARIO A: Livewire Not Available
**Penyebab**: Build assets tidak include Livewire dengan benar
**Solusi**:
```bash
# 1. Rebuild assets
npm run build

# 2. Clear cache
php artisan config:clear
php artisan view:clear

# 3. Restart web server
```

### SKENARIO B: CSRF Token Missing
**Penyebab**: Layout tidak include CSRF meta tag
**Solusi**: Pastikan di `guest.blade.php` ada:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### SKENARIO C: Assets Not Loading
**Penyebab**: Path assets salah atau build gagal
**Solusi**:
```bash
# 1. Cek build manifest
cat public/build/manifest.json

# 2. Rebuild jika perlu
npm run build

# 3. Cek permission files
chmod -R 755 public/build/
```

### SKENARIO D: Form Fallback ke GET
**Penyebab**: JavaScript error atau Livewire tidak ter-initialize
**Solusi**: Cek console logs untuk error JavaScript

## 🚀 SOLUSI BERDASARKAN TEMUAN

### Jika Debug Menunjukkan Livewire Tidak Load:

1. **Cek Build Assets**:
```bash
ls -la public/build/assets/js/
# Harus ada: livewire-*.js
```

2. **Cek Layout Guest**:
```blade
<!-- Harus ada di guest.blade.php -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireScripts
```

3. **Rebuild Production**:
```bash
npm run build
php artisan optimize
```

### Jika Debug Menunjukkan CSRF Error:

1. **Cek Meta Tag**:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

2. **Cek Session Config**:
```env
SESSION_DRIVER=file  # atau redis
SESSION_DOMAIN=null  # atau domain yang benar
```

### Jika Debug Menunjukkan Network Error:

1. **Cek Web Server Config**:
```nginx
# Nginx - pastikan static files bisa diakses
location /build/ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

2. **Cek File Permissions**:
```bash
chmod -R 755 public/
chown -R www-data:www-data public/
```

## 📊 MONITORING PRODUCTION

### Real-time Debugging:
```bash
# 1. Monitor Laravel logs
tail -f storage/logs/laravel.log

# 2. Monitor web server logs
tail -f /var/log/nginx/error.log

# 3. Monitor access logs
tail -f /var/log/nginx/access.log | grep -E "(register|login)"
```

### Performance Check:
```bash
# Cek response time
curl -w "@curl-format.txt" -o /dev/null -s "https://your-domain.com/register"
```

## 🔒 SECURITY NOTES

- File `debug.html` hanya untuk troubleshooting
- **HAPUS** setelah masalah selesai:
```bash
rm public/debug.html
```

- Route debug bisa di-disable di production:
```php
// Di routes/web.php, comment atau hapus:
// Route::post('/debug-form-test', ...)
```

## 📞 ESCALATION

Jika semua debug menunjukkan normal tapi masalah masih ada:

1. **Cek Browser Network Tab** saat submit form
2. **Cek apakah ada JavaScript yang override form behavior**
3. **Cek middleware yang mungkin interfere**
4. **Cek server-side redirect logic**

---

**💡 TIPS**: Jalankan debug tool ini setiap kali deploy untuk memastikan semua komponen bekerja dengan benar di production.