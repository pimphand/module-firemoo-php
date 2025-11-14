<?php

namespace Firemoo\Firemoo\Facades;

use Firemoo\Firemoo\Services\Contracts\WebSocketServiceInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @method static resource|false connect(?string $jwtToken = null, ?string $apiKey = null, ?string $websiteUrl = null)
 * @method static bool subscribe($socket, string $channel)
 * @method static bool unsubscribe($socket, string $channel)
 * @method static bool ping($socket)
 * @method static array|null read($socket, int $timeout = 30)
 * @method static void close($socket)
 * @method static array triggerEvent(string $channel, string $event, array $data)
 *
 * @see \App\Services\WebSocketService
 */
class WebSocket extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Firemoo\Firemoo\Services\Contracts\WebSocketServiceInterface::class;
    }
}
