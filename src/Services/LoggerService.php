<?php

namespace Firemoo\Firemoo\Services;

use Firemoo\Firemoo\Services\Contracts\LoggerServiceInterface;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LoggerService implements LoggerServiceInterface
{
    private string $logPath;
    private string $logFile;

    public function __construct()
    {
        $this->logPath = storage_path('logs/firemoo');
        $this->logFile = $this->logPath . '/' . Carbon::now()->format('Y-m-d') . '.log';

        // Create log directory if it doesn't exist
        if (!is_dir($this->logPath)) {
            mkdir($this->logPath, 0755, true);
        }
    }

    /**
     * Log info message
     */
    public function info(string $message, array $context = []): void
    {
        $this->writeLog('INFO', $message, $context);
    }

    /**
     * Log error message
     */
    public function error(string $message, array $context = []): void
    {
        $this->writeLog('ERROR', $message, $context);
    }

    /**
     * Log warning message
     */
    public function warning(string $message, array $context = []): void
    {
        $this->writeLog('WARNING', $message, $context);
    }

    /**
     * Log debug message
     */
    public function debug(string $message, array $context = []): void
    {
        $this->writeLog('DEBUG', $message, $context);
    }

    /**
     * Write log to file
     */
    private function writeLog(string $level, string $message, array $context = []): void
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        $logMessage = "[{$timestamp}] [{$level}] {$message}{$contextStr}" . PHP_EOL;

        file_put_contents($this->logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}
