# Firemoo

[![Latest Version on Packagist](https://img.shields.io/packagist/v/your-vendor-name/firemoo.svg?style=flat-square)](https://packagist.org/packages/your-vendor-name/firemoo)
[![Total Downloads](https://img.shields.io/packagist/dt/your-vendor-name/firemoo.svg?style=flat-square)](https://packagist.org/packages/your-vendor-name/firemoo)

Laravel package untuk integrasi dengan Firestore-like API dan WebSocket realtime.

## Features

- ✅ Firestore-like API integration (Collections & Documents)
- ✅ WebSocket realtime connections
- ✅ Clean code architecture dengan dependency injection
- ✅ File-based logging (bukan console)
- ✅ Support API Key dan JWT authentication
- ✅ Laravel Facades untuk kemudahan penggunaan
- ✅ Fully tested dan documented

## Installation

> **⚠️ PENTING:** Package ini belum di-publish ke Packagist. Untuk install, gunakan **Local Path** atau **Git Repository**.

### Opsi 1: Install via Local Path (Recommended untuk Development)

**Langkah 1:** Tambahkan repository ke `composer.json` di project Laravel Anda:

**Edit `composer.json` di project Laravel Anda**, tambahkan bagian `repositories` (jika belum ada):

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

> **⚠️ PENTING:** 
> - Ganti `/home/co-026/Project/VueJS/Database/module` dengan path absolut ke folder `module/` di sistem Anda
> - Gunakan `symlink: false` untuk menghindari file Laravel project muncul di vendor
> - Lihat `composer.example.json` untuk contoh konfigurasi lengkap

**Langkah 2:** Install package dengan constraint version:

```bash
composer require pimphand/firemoo:@dev
```

> **⚠️ PENTING:** Harus menggunakan constraint `@dev` karena package belum di-publish dan menggunakan `minimum-stability: dev`

### Opsi 2: Install via Git Repository

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

### Opsi 3: Install via Packagist (Jika sudah terpublish)

Jika package sudah terpublish di Packagist:

```bash
composer require pimphand/firemoo
```

### Publish Config File

Setelah install, publish config file (optional):

```bash
php artisan vendor:publish --tag=firemoo-config
```

### Troubleshooting

**Error: "Could not find a version matching minimum-stability (stable)"**

Error ini terjadi karena:
- Package belum di-publish ke Packagist
- Composer tidak bisa menemukan package di repository default
- Constraint version tidak eksplisit

**Solution:** 
1. **Pastikan sudah menambahkan repository path di `composer.json` project Laravel Anda** (sangat penting!)
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

2. **Gunakan constraint version `@dev` saat install** (WAJIB):
   ```bash
   composer require pimphand/firemoo:@dev
   ```
   Bukan: `composer require pimphand/firemoo` ❌

3. **Verifikasi path benar:**
   ```bash
   # Pastikan path benar
   ls -la /home/co-026/Project/VueJS/Database/module/composer.json
   ```

4. Lihat [INSTALLATION.md](INSTALLATION.md) untuk panduan lengkap

**Error: File Laravel project muncul di vendor**

**Solution:**
1. Gunakan `symlink: false` di repository config
2. Pastikan `.gitattributes` dan `composer.json` sudah dikonfigurasi dengan benar
3. Lihat [INSTALLATION.md](INSTALLATION.md) untuk detail

Lihat [INSTALLATION.md](INSTALLATION.md) untuk panduan instalasi lengkap dan troubleshooting.

## Configuration

Tambahkan ke `.env`:

```env
FIRESTORE_API_URL=http://127.0.0.1:9090
FIRESTORE_WS_URL=ws://127.0.0.1:9090/websocket
FIRESTORE_AUTH_METHOD=api_key
FIRESTORE_API_KEY=your-api-key
FIRESTORE_WEBSITE_URL=https://your-website.com
```

## Quick Start

### Firestore Operations

```php
use Firemoo\Firemoo\Facades\Firestore;

// Create collection
$collection = Firestore::createCollection('tasks');

// Create document
$document = Firestore::createDocument($collectionId, [
    'title' => 'Task 1',
    'status' => 'pending'
]);

// Get documents
$documents = Firestore::getDocuments($collectionId, page: 1, limit: 10);
```

### WebSocket Operations

```php
use Firemoo\Firemoo\Facades\WebSocket;

// Connect
$socket = WebSocket::connect(
    apiKey: 'your-api-key',
    websiteUrl: 'https://your-website.com'
);

// Subscribe to channel
WebSocket::subscribe($socket, 'tasks');

// Read messages
while (true) {
    $message = WebSocket::read($socket, timeout: 30);
    if ($message) {
        // Handle message
    }
}
```

## Documentation

Lihat [FIREMOO_USAGE.md](FIREMOO_USAGE.md) untuk dokumentasi lengkap.

## Requirements

- PHP >= 8.2
- Laravel >= 10.0

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## Support

Jika ada pertanyaan atau issue, silakan buat issue di GitHub repository.

