# Panduan Instalasi Package Firemoo

Dokumen ini menjelaskan cara install package Firemoo dengan benar untuk menghindari masalah file Laravel project muncul di vendor.

## ⚠️ Masalah yang Sering Terjadi

Ketika install via local path, semua file Laravel project (seperti `app/`, `artisan`, `routes/`, dll) ikut muncul di `vendor/pimphand/firemoo`. Ini terjadi karena:

1. Folder `module/` berisi file Laravel project dan package
2. Saat install via local path dengan symlink, Composer akan membuat symlink ke seluruh folder
3. File yang tidak perlu ikut terlihat di vendor

## ✅ Solusi: Install dengan Konfigurasi yang Benar

### Opsi 1: Install via Local Path (Tanpa Symlink) - RECOMMENDED

**Langkah 1:** Tambahkan repository ke `composer.json` di project Laravel Anda dengan `symlink: false`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/home/co-026/Project/VueJS/Database/module",
            "options": {
                "symlink": false
            }
        }
    ]
}
```

**Langkah 2:** Install package:

```bash
composer require pimphand/firemoo:@dev
```

**Keuntungan:**
- Composer akan **copy file** (bukan symlink)
- Hanya file yang **tidak di-exclude** di `.gitattributes` dan `composer.json` yang akan di-copy
- File Laravel project akan diabaikan karena sudah di-exclude di konfigurasi

### Opsi 2: Install via Local Path (Dengan Symlink)

Jika ingin menggunakan symlink (untuk development):

**Langkah 1:** Tambahkan repository dengan `symlink: true`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/home/co-026/Project/VueJS/Database/module",
            "options": {
                "symlink": true
            }
        }
    ]
}
```

**Catatan:** Dengan symlink, semua file akan terlihat. Pastikan file Laravel project sudah di-exclude di `.gitattributes` dan `composer.json` (sudah dikonfigurasi).

### Opsi 3: Install via Git Repository

Jika package sudah di-push ke repository Git:

**Langkah 1:** Tambahkan repository:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/username/firemoo.git"
        }
    ]
}
```

**Langkah 2:** Install package:

```bash
composer require pimphand/firemoo:dev-main
```

**Keuntungan:**
- Hanya file yang di-push ke Git yang akan di-install
- File yang di-exclude di `.gitattributes` akan diabaikan saat package di-archive

### Opsi 4: Install via Packagist (Jika sudah terpublish)

Jika package sudah terpublish di Packagist:

```bash
composer require pimphand/firemoo
```

## 📋 File yang Akan Di-Install

Setelah install, hanya file berikut yang akan muncul di `vendor/pimphand/firemoo`:

```
vendor/pimphand/firemoo/
├── composer.json
├── LICENSE
├── README.md
├── README.PACKAGE.md
├── phpunit.xml
├── config/
│   └── firemoo.php
├── src/
│   ├── Facades/
│   │   ├── Firestore.php
│   │   └── WebSocket.php
│   ├── FiremooServiceProvider.php
│   ├── helpers.php
│   └── Services/
│       ├── Contracts/
│       │   ├── FirestoreServiceInterface.php
│       │   ├── HttpClientServiceInterface.php
│       │   ├── LoggerServiceInterface.php
│       │   └── WebSocketServiceInterface.php
│       ├── FirestoreService.php
│       ├── HttpClientService.php
│       ├── LoggerService.php
│       └── WebSocketService.php
└── tests/
    ├── Feature/
    │   └── ExampleTest.php
    ├── Unit/
    │   └── ExampleTest.php
    ├── Pest.php
    └── TestCase.php
```

## ❌ File yang TIDAK Akan Di-Install

File berikut akan **diabaikan** karena sudah di-exclude di konfigurasi:

- `/app/` - Laravel app folder
- `/artisan` - Laravel CLI
- `/routes/` - Laravel routes
- `/database/` - Laravel database
- `/storage/` - Laravel storage
- `/public/` - Laravel public
- `/resources/` - Laravel resources
- `/bootstrap/` - Laravel bootstrap
- `/config/app.php`, `/config/auth.php`, dll - Config Laravel (kecuali `firemoo.php`)
- `vite.config.js` - Laravel build config
- `package.json` - Laravel package.json
- `composer.laravel.json` - Laravel composer.json

## 🔧 Verifikasi Instalasi

Setelah install, verifikasi bahwa hanya file package yang muncul:

```bash
# Lihat isi vendor/pimphand/firemoo
ls -la vendor/pimphand/firemoo/

# Pastikan tidak ada folder app/, artisan, routes/, dll
# Hanya ada: src/, config/, tests/, composer.json, LICENSE, README.md
```

## 🐛 Troubleshooting

### Problem: File Laravel project masih muncul di vendor

**Solution 1:** Pastikan menggunakan `symlink: false` di repository config:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/module",
            "options": {
                "symlink": false
            }
        }
    ]
}
```

**Solution 2:** Hapus package dan install ulang:

```bash
composer remove pimphand/firemoo
composer require pimphand/firemoo:@dev
```

**Solution 3:** Pastikan file Laravel project sudah di-exclude di `.gitattributes` dan `composer.json` (sudah dikonfigurasi).

### Problem: Config file tidak ditemukan

**Solution:** Pastikan file `config/firemoo.php` ada di package:

```bash
ls -la /path/to/module/config/firemoo.php
```

Jika tidak ada, pastikan file sudah dibuat.

## 📝 Catatan Penting

1. **Symlink vs Copy:** 
   - `symlink: false` = Copy file (recommended, file Laravel akan diabaikan)
   - `symlink: true` = Symlink (semua file akan terlihat, tapi akan di-exclude saat di-archive)

2. **Export Ignore:**
   - File yang di-exclude di `.gitattributes` dengan `export-ignore` akan diabaikan saat package di-archive
   - File yang di-exclude di `composer.json` dengan `archive.exclude` akan diabaikan saat package di-archive

3. **Local Path:**
   - Pastikan path mengarah ke folder `module/` yang berisi `composer.json` package
   - Jangan gunakan path yang mengarah ke folder Laravel project penuh

## ✅ Checklist

Sebelum install, pastikan:

- [ ] File `config/firemoo.php` sudah ada di package
- [ ] File `.gitattributes` sudah dikonfigurasi dengan benar
- [ ] File `composer.json` sudah memiliki konfigurasi `archive.exclude`
- [ ] Repository config menggunakan `symlink: false` (recommended)
- [ ] Path repository mengarah ke folder package yang benar

Setelah install, pastikan:

- [ ] Hanya file package yang muncul di `vendor/pimphand/firemoo`
- [ ] File Laravel project tidak muncul di vendor
- [ ] Package bisa digunakan dengan benar

