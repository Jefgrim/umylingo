<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    private const MAX_LINES = 500;

    /**
     * Display logs with filtering by type.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $path = storage_path('logs/laravel.log');

        if (!file_exists($path)) {
            return view('admin.logs', [
                'logs' => [],
                'source' => $path,
                'type' => $type,
                'types' => $this->getAvailableTypes(),
            ]);
        }

        $rawLines = array_slice(
            file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES),
            -self::MAX_LINES
        );

        $logs = $this->parseLogs($rawLines, $type);

        return view('admin.logs', [
            'logs' => $logs,
            'source' => $path,
            'type' => $type,
            'types' => $this->getAvailableTypes(),
            'levelColor' => fn($level) => $this->getLevelColor($level),
        ]);
    }

    /**
     * Parse log lines and extract structured data.
     */
    private function parseLogs(array $lines, string $type = 'all'): array
    {
        $logs = [];

        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $parsed = $this->parseLine($line);
            if (!$parsed) {
                continue;
            }

            if ($type !== 'all' && $parsed['type'] !== $type) {
                continue;
            }

            $logs[] = $parsed;
        }

        // Return in reverse chronological order
        return array_reverse($logs);
    }

    /**
     * Parse a single log line into structured data.
     */
    private function parseLine(string $line): ?array
    {
        // Format: [2024-12-09 10:30:45] local.WARNING: ... {"key": "value"}
        if (!preg_match('/^\[([^\]]+)\]\s+(\w+)\.(\w+):\s+(.+?)\s+(\{.*\})?$/', $line, $matches)) {
            return null;
        }

        $timestamp = $matches[1];
        $channel = $matches[2];
        $level = strtolower($matches[3]);
        $message = $matches[4];
        $contextJson = $matches[5] ?? null;

        $context = [];
        if ($contextJson) {
            $context = json_decode($contextJson, true) ?? [];
        }

        $type = $this->categorizeLog($message, $level);

        return [
            'timestamp' => $timestamp,
            'channel' => $channel,
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'type' => $type,
            'raw' => $line,
        ];
    }

    /**
     * Categorize log by message content.
     */
    private function categorizeLog(string $message, string $level): string
    {
        if (stripos($message, 'login') !== false) {
            return 'login';
        }
        if (stripos($message, 'deck') !== false) {
            return 'deck';
        }
        if (stripos($message, 'card') !== false) {
            return 'card';
        }
        if (stripos($message, 'user') !== false) {
            return 'user';
        }
        if (stripos($message, 'request') !== false) {
            return 'request';
        }
        if ($level === 'error' || $level === 'critical') {
            return 'error';
        }
        if ($level === 'warning') {
            return 'warning';
        }
        return 'other';
    }

    /**
     * Get color for log level.
     */
    private function getLevelColor(string $level): string
    {
        return match($level) {
            'error' => '#ef4444',
            'critical' => '#dc2626',
            'warning' => '#f97316',
            'info' => '#3b82f6',
            'debug' => '#8b5cf6',
            default => '#6b7280',
        };
    }

    /**
     * Get available log types.
     */
    private function getAvailableTypes(): array
    {
        return [
            'all' => 'All Logs',
            'login' => 'Login Attempts',
            'deck' => 'Deck Operations',
            'card' => 'Card Operations',
            'user' => 'User Operations',
            'request' => 'HTTP Requests',
            'error' => 'Errors',
            'warning' => 'Warnings',
        ];
    }
}
