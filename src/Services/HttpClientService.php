<?php

namespace Firemoo\Firemoo\Services;

use Firemoo\Firemoo\Services\Contracts\HttpClientServiceInterface;
use Firemoo\Firemoo\Services\Contracts\LoggerServiceInterface;
use Illuminate\Support\Facades\Http;
use Exception;

class HttpClientService implements HttpClientServiceInterface
{
    private array $defaultHeaders = [];
    private ?string $baseUrl = null;
    private LoggerServiceInterface $logger;

    public function __construct(LoggerServiceInterface $logger)
    {
        $this->logger = $logger;
        $this->baseUrl = config('firemoo.api_url', env('FIRESTORE_API_URL', 'http://127.0.0.1:9090'));
    }

    /**
     * Make HTTP request
     */
    public function request(string $method, string $url, array $options = []): array
    {
        try {
            // Build full URL
            $fullUrl = str_starts_with($url, 'http') ? $url : rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');

            // Merge headers
            $headers = array_merge($this->defaultHeaders, $options['headers'] ?? []);

            // Prepare request
            $request = Http::withHeaders($headers);

            // Add timeout (use config default if not specified)
            $timeout = $options['timeout'] ?? config('firemoo.timeout', 30);
            $request->timeout($timeout);

            // Make request based on method
            $response = match (strtoupper($method)) {
                'GET' => $request->get($fullUrl, $options['query'] ?? []),
                'POST' => $request->post($fullUrl, $options['json'] ?? $options['body'] ?? []),
                'PUT' => $request->put($fullUrl, $options['json'] ?? $options['body'] ?? []),
                'PATCH' => $request->patch($fullUrl, $options['json'] ?? $options['body'] ?? []),
                'DELETE' => $request->delete($fullUrl, $options['query'] ?? []),
                default => throw new Exception("Unsupported HTTP method: {$method}"),
            };

            // Log request
            $this->logger->debug("HTTP {$method} {$fullUrl}", [
                'headers' => $headers,
                'status' => $response->status(),
            ]);

            // Check for errors
            if ($response->failed()) {
                $errorMessage = $response->json()['error'] ?? $response->body();
                $this->logger->error("HTTP {$method} {$fullUrl} failed", [
                    'status' => $response->status(),
                    'error' => $errorMessage,
                ]);
                throw new Exception("HTTP request failed: {$errorMessage}", $response->status());
            }

            return [
                'status' => $response->status(),
                'data' => $response->json() ?? $response->body(),
                'headers' => $response->headers(),
            ];
        } catch (Exception $e) {
            $this->logger->error("HTTP request exception: {$e->getMessage()}", [
                'method' => $method,
                'url' => $url,
                'exception' => get_class($e),
            ]);
            throw $e;
        }
    }

    /**
     * Set default headers
     */
    public function setHeaders(array $headers): self
    {
        $this->defaultHeaders = array_merge($this->defaultHeaders, $headers);
        return $this;
    }

    /**
     * Set API key and website URL for authentication
     */
    public function setApiKey(string $apiKey, string $websiteUrl): self
    {
        $this->defaultHeaders['X-API-Key'] = $apiKey;
        $this->defaultHeaders['X-Website-Url'] = $websiteUrl;
        return $this;
    }

    /**
     * Set JWT token for authentication
     */
    public function setJwtToken(string $token): self
    {
        $this->defaultHeaders['Authorization'] = "Bearer {$token}";
        return $this;
    }
}
