<?php

namespace Firemoo\Firemoo\Services\Contracts;

interface WebSocketServiceInterface
{
    /**
     * Connect to WebSocket server
     *
     * @param string|null $jwtToken
     * @param string|null $apiKey
     * @param string|null $websiteUrl
     * @return resource|false
     * @throws \Exception
     */
    public function connect(?string $jwtToken = null, ?string $apiKey = null, ?string $websiteUrl = null);

    /**
     * Subscribe to a channel
     *
     * @param resource $socket
     * @param string $channel
     * @return bool
     * @throws \Exception
     */
    public function subscribe($socket, string $channel): bool;

    /**
     * Unsubscribe from a channel
     *
     * @param resource $socket
     * @param string $channel
     * @return bool
     * @throws \Exception
     */
    public function unsubscribe($socket, string $channel): bool;

    /**
     * Send ping to server
     *
     * @param resource $socket
     * @return bool
     * @throws \Exception
     */
    public function ping($socket): bool;

    /**
     * Read message from WebSocket
     *
     * @param resource $socket
     * @param int $timeout
     * @return array|null
     * @throws \Exception
     */
    public function read($socket, int $timeout = 30): ?array;

    /**
     * Close WebSocket connection
     *
     * @param resource $socket
     * @return void
     */
    public function close($socket): void;

    /**
     * Trigger a channel event via HTTP API
     *
     * @param string $channel
     * @param string $event
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function triggerEvent(string $channel, string $event, array $data): array;
}
