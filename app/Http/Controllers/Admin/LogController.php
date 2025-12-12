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
            if (empty(trim($line))) {
                continue;
            }

            $parsed = $this->parseLine($line);
            if (!$parsed) {
                // Skip unparseable lines instead of failing
                continue;
            }

            // Filter by type if not 'all'
            if ($type !== 'all' && strtolower($parsed['type']) !== strtolower($type)) {
                continue;
            }

            $logs[] = $parsed;
        }

        // Return in reverse chronological order (most recent first)
        return array_reverse($logs);
    }

    /**
     * Parse a single log line into structured data.
     */
    private function parseLine(string $line): ?array
    {
        // Format 1: [2024-12-09 10:30:45] local.WARNING: ... {"key": "value"}
        // Format 2: [2024-12-09 10:30:45] local.WARNING: ...
        $pattern = '/^\[([^\]]+)\]\s+(\w+)\.(\w+):\s+(.+?)(?:\s+(\{.+\}))?$/s';
        
        if (!preg_match($pattern, $line, $matches)) {
            // Try simpler format without channel
            $simplePattern = '/^\[([^\]]+)\]\s+(\w+):\s+(.+?)(?:\s+(\{.+\}))?$/s';
            if (!preg_match($simplePattern, $line, $matches)) {
                return null;
            }
            // Adjust matches for simpler format
            $timestamp = $matches[1];
            $channel = 'local';
            $level = strtolower($matches[2]);
            $message = $matches[3];
            $contextJson = $matches[4] ?? null;
        } else {
            $timestamp = $matches[1];
            $channel = $matches[2];
            $level = strtolower($matches[3]);
            $message = $matches[4];
            $contextJson = $matches[5] ?? null;
        }

        $context = [];
        if ($contextJson && trim($contextJson)) {
            try {
                $decoded = json_decode($contextJson, true);
                if (is_array($decoded)) {
                    $context = $decoded;
                }
            } catch (\Exception $e) {
                // If JSON decode fails, just leave context empty
            }
        }

        $type = $this->categorizeLog($message, $level, $context);

        return [
            'timestamp' => $timestamp,
            'channel' => $channel,
            'level' => $level,
            'message' => trim($message),
            'context' => $context,
            'type' => $type,
            'raw' => $line,
        ];
    }

    /**
     * Categorize log by message content and context.
     */
    private function categorizeLog(string $message, string $level, array $context = []): string
    {
        $messageLower = strtolower($message);
        $contextString = strtolower(json_encode($context));
        
        // PRIORITY 1: Level-based categorization (errors and warnings first)
        if ($level === 'error' || $level === 'critical' || $level === 'emergency' || $level === 'alert') {
            return 'error';
        }
        
        if ($level === 'warning') {
            return 'warning';
        }
        
        // PRIORITY 2: Content-based categorization
        
        // Check for authentication/login related
        if (stripos($messageLower, 'login') !== false || 
            stripos($messageLower, 'auth') !== false ||
            stripos($messageLower, 'logout') !== false ||
            stripos($messageLower, 'authenticated') !== false) {
            return 'login';
        }
        
        // Check for deck operations
        if (stripos($messageLower, 'deck') !== false ||
            stripos($contextString, 'deck') !== false) {
            return 'deck';
        }
        
        // Check for card operations
        if (stripos($messageLower, 'card') !== false ||
            stripos($contextString, 'card') !== false) {
            return 'card';
        }
        
        // Check for user operations
        if (stripos($messageLower, 'user') !== false ||
            stripos($contextString, 'user') !== false) {
            return 'user';
        }
        
        // Check for HTTP requests
        if (stripos($messageLower, 'request') !== false ||
            stripos($messageLower, 'http') !== false ||
            stripos($messageLower, 'get ') !== false ||
            stripos($messageLower, 'post ') !== false ||
            stripos($messageLower, 'put ') !== false ||
            stripos($messageLower, 'delete ') !== false ||
            stripos($messageLower, 'patch ') !== false) {
            return 'request';
        }
        
        // PRIORITY 3: Default to 'other' for info, debug, notice logs
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
            'other' => 'Other',
        ];
    }
}
