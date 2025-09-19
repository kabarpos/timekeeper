# 🔧 PANDUAN PERBAIKAN PRODUCTION - TimeKeeper

## 🚨 MASALAH YANG TERJADI
Parameter sensitif (email, password) muncul di URL setelah form submission di production, padahal di development berjalan normal.

## 🔍 ANALISIS ROOT CAUSE

### 1. **Perbedaan Environment Development vs Production**
- **Development**: Menggunakan Vite dev server (HMR aktif)
- **Production**: Menggunakan build assets statis

### 2. **Masalah Utama yang Ditemukan**
1. **Livewire Scripts tidak dimuat dengan benar di production**
2. **Konfigurasi SESSION_DOMAIN yang salah**
3. **Build assets tidak ter-update setelah perbaikan**
4. **CSRF token mungkin tidak valid**

## ✅ SOLUSI LENGKAP

### LANGKAH 1: Perbaiki Konfigurasi Production Environment

```bash
# 1. Backup file .env production yang lama
cp .env.production .env.production.backup

# 2. Update konfigurasi production
```

**Perbaikan di `.env.production`:**
```env
# Ganti domain sesuai dengan domain aktual
APP_URL=https://your-actual-domain.com

# Perbaiki konfigurasi session
SESSION_DOMAIN=null  # atau sesuaikan dengan domain aktual
SESSION_SECURE_COOKIES=false  # true jika menggunakan HTTPS
SESSION_SAME_SITE=lax

# Pastikan CSRF aktif
CSRF_PROTECTION=true
```

### LANGKAH 2: Rebuild Assets Production

```bash
# 1. Hapus build lama
rm -rf public/build

# 2. Build ulang dengan konfigurasi terbaru
npm run build

# 3. Verifikasi build berhasil
ls -la public/build/assets/
```

### LANGKAH 3: Verifikasi Layout Guest

Pastikan file `resources/views/layouts/guest.blade.php` sudah memiliki:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/" wire:navigate>
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
    
    <!-- PENTING: Livewire Scripts harus ada -->
    @livewireScripts
</body>
</html>
```

### LANGKAH 4: Deploy ke Production

```bash
# 1. Upload semua file yang sudah diperbaiki
# - .env.production (dengan konfigurasi yang benar)
# - resources/views/layouts/guest.blade.php
# - public/build/ (folder build yang baru)

# 2. Di server production, jalankan:
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 3. Restart web server (nginx/apache)
sudo systemctl restart nginx
# atau
sudo systemctl restart apache2
```

### LANGKAH 5: Testing Production

1. **Buka halaman registrasi**: `https://your-domain.com/register`
2. **Isi form registrasi** dengan data test
3. **Submit form** dan pastikan:
   - Form menggunakan POST method (bukan GET)
   - Tidak ada parameter sensitif di URL
   - Redirect berjalan normal

## 🔧 TROUBLESHOOTING

### Jika Masalah Masih Terjadi:

#### 1. **Cek Console Browser**
```javascript
// Buka Developer Tools > Console
// Pastikan tidak ada error JavaScript
// Pastikan Livewire loaded dengan benar
```

#### 2. **Cek Network Tab**
- Form submission harus menggunakan method POST
- Request headers harus ada `X-CSRF-TOKEN`
- Response tidak boleh redirect dengan parameter di URL

#### 3. **Cek Server Logs**
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Web server logs
tail -f /var/log/nginx/error.log
# atau
tail -f /var/log/apache2/error.log
```

#### 4. **Force Clear Cache**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

## 🎯 CHECKLIST FINAL

- [ ] File `.env.production` sudah diperbaiki
- [ ] `SESSION_DOMAIN` sudah disesuaikan
- [ ] `APP_URL` sudah benar
- [ ] Layout `guest.blade.php` sudah ada `@livewireScripts`
- [ ] Build production sudah di-update (`npm run build`)
- [ ] Cache sudah di-clear di server
- [ ] Web server sudah di-restart
- [ ] Testing form registrasi/login berhasil
- [ ] Tidak ada parameter sensitif di URL

## 🚀 HASIL YANG DIHARAPKAN

Setelah mengikuti panduan ini:
1. ✅ Form registrasi/login menggunakan AJAX POST
2. ✅ Tidak ada parameter sensitif di URL
3. ✅ Livewire berfungsi dengan benar di production
4. ✅ CSRF protection aktif
5. ✅ Session management aman

---

**💡 CATATAN PENTING:**
- Selalu backup file konfigurasi sebelum melakukan perubahan
- Test di staging environment sebelum deploy ke production
- Monitor logs setelah deployment untuk memastikan tidak ada error baru