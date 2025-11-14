<?php

namespace Firemoo\Firemoo\Services\Contracts;

interface LoggerServiceInterface
{
    /**
     * Log info message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function info(string $message, array $context = []): void;

    /**
     * Log error message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function error(string $message, array $context = []): void;

    /**
     * Log warning message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function warning(string $message, array $context = []): void;

    /**
     * Log debug message
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function debug(string $message, array $context = []): void;
}
