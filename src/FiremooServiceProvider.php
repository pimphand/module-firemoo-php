<?php

namespace Firemoo\Firemoo;

use Firemoo\Firemoo\Services\Contracts\FirestoreServiceInterface;
use Firemoo\Firemoo\Services\Contracts\HttpClientServiceInterface;
use Firemoo\Firemoo\Services\Contracts\LoggerServiceInterface;
use Firemoo\Firemoo\Services\Contracts\WebSocketServiceInterface;
use Firemoo\Firemoo\Services\FirestoreService;
use Firemoo\Firemoo\Services\HttpClientService;
use Firemoo\Firemoo\Services\LoggerService;
use Firemoo\Firemoo\Services\WebSocketService;
use Illuminate\Support\ServiceProvider;

class FiremooServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/firemoo.php',
            'firemoo'
        );

        // Register Logger Service
        $this->app->singleton(LoggerServiceInterface::class, function ($app) {
            return new LoggerService();
        });

        // Register HTTP Client Service
        $this->app->singleton(HttpClientServiceInterface::class, function ($app) {
            $logger = $app->make(LoggerServiceInterface::class);
            $client = new HttpClientService($logger);

            // Set default authentication if configured
            $authMethod = config('firemoo.default_auth_method', 'api_key');
            if ($authMethod === 'api_key') {
                $apiKey = config('firemoo.default_api_key');
                $websiteUrl = config('firemoo.default_website_url');
                if ($apiKey && $websiteUrl) {
                    $client->setApiKey($apiKey, $websiteUrl);
                }
            }

            return $client;
        });

        // Register Firestore Service
        $this->app->singleton(FirestoreServiceInterface::class, function ($app) {
            $httpClient = $app->make(HttpClientServiceInterface::class);
            $logger = $app->make(LoggerServiceInterface::class);
            return new FirestoreService($httpClient, $logger);
        });

        // Register WebSocket Service
        $this->app->singleton(WebSocketServiceInterface::class, function ($app) {
            $httpClient = $app->make(HttpClientServiceInterface::class);
            $logger = $app->make(LoggerServiceInterface::class);
            return new WebSocketService($httpClient, $logger);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/firemoo.php' => config_path('firemoo.php'),
        ], 'firemoo-config');
    }
}
