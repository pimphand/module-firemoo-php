<?php

if (!function_exists('firestore')) {
    /**
     * Get Firestore service instance
     *
     * @return \Firemoo\Firemoo\Services\Contracts\FirestoreServiceInterface
     */
    function firestore()
    {
        return app(\Firemoo\Firemoo\Services\Contracts\FirestoreServiceInterface::class);
    }
}

if (!function_exists('websocket')) {
    /**
     * Get WebSocket service instance
     *
     * @return \Firemoo\Firemoo\Services\Contracts\WebSocketServiceInterface
     */
    function websocket()
    {
        return app(\Firemoo\Firemoo\Services\Contracts\WebSocketServiceInterface::class);
    }
}
