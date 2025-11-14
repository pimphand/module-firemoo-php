<?php

namespace Firemoo\Firemoo\Services;

use Firemoo\Firemoo\Services\Contracts\WebSocketServiceInterface;
use Firemoo\Firemoo\Services\Contracts\HttpClientServiceInterface;
use Firemoo\Firemoo\Services\Contracts\LoggerServiceInterface;
use Exception;

class WebSocketService implements WebSocketServiceInterface
{
    private HttpClientServiceInterface $httpClient;
    private LoggerServiceInterface $logger;
    private ?string $baseUrl = null;
    private ?string $wsUrl = null;

    public function __construct(
        HttpClientServiceInterface $httpClient,
        LoggerServiceInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
        $this->baseUrl = config('firemoo.api_url', env('FIRESTORE_API_URL', 'http://127.0.0.1:9090'));
        $this->wsUrl = config('firemoo.ws_url', env('FIRESTORE_WS_URL', 'ws://127.0.0.1:9090/websocket'));
    }

    /**
     * Connect to WebSocket server
     */
    public function connect(?string $jwtToken = null, ?string $apiKey = null, ?string $websiteUrl = null)
    {
        try {
            // Build WebSocket URL
            $url = $this->wsUrl;

            // Parse URL to get host and path
            $parsed = parse_url($url);
            $host = $parsed['host'] ?? '127.0.0.1';
            $port = $parsed['port'] ?? 9090;
            $path = $parsed['path'] ?? '/websocket';
            $scheme = $parsed['scheme'] ?? 'ws';

            // Create socket connection
            $socket = @fsockopen($host, $port, $errno, $errstr, 10);
            if (!$socket) {
                throw new Exception("Failed to connect to WebSocket server: {$errstr} ({$errno})");
            }

            // Build WebSocket handshake headers
            $key = base64_encode(random_bytes(16));
            $headers = [
                "GET {$path} HTTP/1.1",
                "Host: {$host}:{$port}",
                "Upgrade: websocket",
                "Connection: Upgrade",
                "Sec-WebSocket-Key: {$key}",
                "Sec-WebSocket-Version: 13",
            ];

            // Add authentication headers
            if ($jwtToken !== null) {
                $headers[] = "Authorization: Bearer {$jwtToken}";
            }

            if ($apiKey !== null && $websiteUrl !== null) {
                $headers[] = "X-API-Key: {$apiKey}";
                $headers[] = "X-Website-Url: {$websiteUrl}";
            }

            $headers[] = "\r\n";

            // Send handshake
            $handshake = implode("\r\n", $headers);
            fwrite($socket, $handshake);

            // Read response
            $response = '';
            while (!feof($socket)) {
                $line = fgets($socket, 1024);
                $response .= $line;
                if (strlen($line) < 2) {
                    break;
                }
            }

            // Check if handshake was successful
            if (!str_contains($response, 'HTTP/1.1 101')) {
                fclose($socket);
                throw new Exception("WebSocket handshake failed: {$response}");
            }

            $this->logger->info("WebSocket connected", [
                'host' => $host,
                'port' => $port,
            ]);

            return $socket;
        } catch (Exception $e) {
            $this->logger->error("Failed to connect to WebSocket: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Subscribe to a channel
     */
    public function subscribe($socket, string $channel): bool
    {
        try {
            $command = [
                'action' => 'subscribe',
                'channel' => $channel,
            ];

            $this->sendCommand($socket, $command);
            $this->logger->info("Subscribed to channel", ['channel' => $channel]);

            return true;
        } catch (Exception $e) {
            $this->logger->error("Failed to subscribe to channel: {$e->getMessage()}", ['channel' => $channel]);
            throw $e;
        }
    }

    /**
     * Unsubscribe from a channel
     */
    public function unsubscribe($socket, string $channel): bool
    {
        try {
            $command = [
                'action' => 'unsubscribe',
                'channel' => $channel,
            ];

            $this->sendCommand($socket, $command);
            $this->logger->info("Unsubscribed from channel", ['channel' => $channel]);

            return true;
        } catch (Exception $e) {
            $this->logger->error("Failed to unsubscribe from channel: {$e->getMessage()}", ['channel' => $channel]);
            throw $e;
        }
    }

    /**
     * Send ping to server
     */
    public function ping($socket): bool
    {
        try {
            $command = ['action' => 'ping'];
            $this->sendCommand($socket, $command);
            return true;
        } catch (Exception $e) {
            $this->logger->error("Failed to send ping: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Read message from WebSocket
     */
    public function read($socket, int $timeout = 30): ?array
    {
        try {
            // Set socket timeout
            stream_set_timeout($socket, $timeout);

            // Read frame
            $data = fread($socket, 8192);
            if ($data === false || empty($data)) {
                return null;
            }

            // Decode WebSocket frame (simplified - for production use a proper WebSocket library)
            $decoded = $this->decodeFrame($data);
            if ($decoded === null) {
                return null;
            }

            // Parse JSON
            $message = json_decode($decoded, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->warning("Failed to parse WebSocket message", [
                    'error' => json_last_error_msg(),
                    'data' => $decoded,
                ]);
                return null;
            }

            return $message;
        } catch (Exception $e) {
            $this->logger->error("Failed to read from WebSocket: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Close WebSocket connection
     */
    public function close($socket): void
    {
        if (is_resource($socket)) {
            fclose($socket);
            $this->logger->info("WebSocket connection closed");
        }
    }

    /**
     * Trigger a channel event via HTTP API
     */
    public function triggerEvent(string $channel, string $event, array $data): array
    {
        try {
            $response = $this->httpClient->request('POST', '/api/realtime/trigger', [
                'json' => [
                    'channel' => $channel,
                    'event' => $event,
                    'data' => $data,
                ],
            ]);

            $this->logger->info("Channel event triggered", [
                'channel' => $channel,
                'event' => $event,
            ]);

            return $response['data'];
        } catch (Exception $e) {
            $this->logger->error("Failed to trigger event: {$e->getMessage()}", [
                'channel' => $channel,
                'event' => $event,
            ]);
            throw $e;
        }
    }

    /**
     * Send command to WebSocket
     */
    private function sendCommand($socket, array $command): void
    {
        $json = json_encode($command);
        $frame = $this->encodeFrame($json);
        fwrite($socket, $frame);
    }

    /**
     * Encode WebSocket frame (simplified implementation)
     */
    private function encodeFrame(string $data): string
    {
        $length = strlen($data);
        $frame = chr(0x81); // FIN + text frame

        if ($length < 126) {
            $frame .= chr($length);
        } elseif ($length < 65536) {
            $frame .= chr(126) . pack('n', $length);
        } else {
            $frame .= chr(127) . pack('N', 0) . pack('N', $length);
        }

        $frame .= $data;
        return $frame;
    }

    /**
     * Decode WebSocket frame (simplified implementation)
     */
    private function decodeFrame(string $data): ?string
    {
        if (strlen($data) < 2) {
            return null;
        }

        $firstByte = ord($data[0]);
        $secondByte = ord($data[1]);

        $masked = ($secondByte & 0x80) !== 0;
        $length = $secondByte & 0x7F;

        $offset = 2;

        if ($length === 126) {
            if (strlen($data) < 4) {
                return null;
            }
            $length = unpack('n', substr($data, $offset, 2))[1];
            $offset += 2;
        } elseif ($length === 127) {
            if (strlen($data) < 10) {
                return null;
            }
            $length = unpack('N', substr($data, $offset + 4, 4))[1];
            $offset += 8;
        }

        if ($masked) {
            if (strlen($data) < $offset + 4) {
                return null;
            }
            $mask = substr($data, $offset, 4);
            $offset += 4;
        }

        if (strlen($data) < $offset + $length) {
            return null;
        }

        $payload = substr($data, $offset, $length);

        if ($masked) {
            $decoded = '';
            for ($i = 0; $i < $length; $i++) {
                $decoded .= $payload[$i] ^ $mask[$i % 4];
            }
            return $decoded;
        }

        return $payload;
    }
}
