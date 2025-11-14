# Firemoo Services

Service untuk menghandle Firestore dan WebSocket API dengan clean code architecture.

## Struktur Service

```
app/
├── Services/
│   ├── Contracts/                    # Interfaces untuk Dependency Injection
│   │   ├── FirestoreServiceInterface.php
│   │   ├── WebSocketServiceInterface.php
│   │   ├── LoggerServiceInterface.php
│   │   └── HttpClientServiceInterface.php
│   ├── FirestoreService.php          # Service untuk Firestore operations
│   ├── WebSocketService.php          # Service untuk WebSocket connections
│   ├── HttpClientService.php         # Service untuk HTTP requests
│   └── LoggerService.php             # Service untuk logging ke file
├── Facades/
│   ├── Firestore.php                 # Facade untuk FirestoreService
│   └── WebSocket.php                 # Facade untuk WebSocketService
└── Providers/
    └── AppServiceProvider.php        # Service registration
```

## Design Principles

1. **Separation of Concerns**: Setiap service memiliki tanggung jawab yang jelas
2. **Dependency Injection**: Menggunakan interfaces untuk loose coupling
3. **Single Responsibility**: Setiap service hanya handle satu concern
4. **Interface Segregation**: Interfaces yang spesifik dan focused
5. **Logging**: Semua operasi di-log ke file (bukan console)

## Service Dependencies

```
FirestoreService
  ├── HttpClientService
  │     └── LoggerService
  └── LoggerService

WebSocketService
  ├── HttpClientService
  │     └── LoggerService
  └── LoggerService
```

## Usage

Lihat `FIREMOO_USAGE.md` di root directory untuk contoh penggunaan lengkap.

## Testing

Service dapat di-test dengan mudah karena menggunakan dependency injection:

```php
// Mock interfaces
$mockHttpClient = Mockery::mock(HttpClientServiceInterface::class);
$mockLogger = Mockery::mock(LoggerServiceInterface::class);

// Inject mocks
$firestore = new FirestoreService($mockHttpClient, $mockLogger);
```

## Logging

Semua log ditulis ke `storage/logs/firemoo/YYYY-MM-DD.log` dengan format:
```
[2024-01-01 12:00:00] [INFO] Collection created: tasks {"collection_id":"..."}
[2024-01-01 12:00:01] [ERROR] Failed to create collection: ... {"name":"tasks"}
```

