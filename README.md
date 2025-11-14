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

Install package via Composer:

```bash
composer require pimphand/firemoo
```

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

