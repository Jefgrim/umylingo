<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OpsController extends Controller
{
    private const MAX_LOG_LINES = 150;
    private const FRESH_WINDOW_SECONDS = 600; // 10 minutes
    private const BACKUP_DIR = 'backups'; // storage/app/backups

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_secret || !$user->two_factor_confirmed_at) {
            return redirect()->route('two-factor.index')->with('status', 'Enable two-factor authentication to access operational data.');
        }

        $lastVerified = (int) $request->session()->get('logs_2fa_passed_at');
        if (!$lastVerified || (time() - $lastVerified) > self::FRESH_WINDOW_SECONDS) {
            return view('admin.logs-verify');
        }

        return view('admin.ops', [
            'logs' => $this->recentLogs(),
            'backups' => $this->listBackups(),
            'health' => $this->healthSnapshot(),
        ]);
    }

    public function createBackup(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_secret || !$user->two_factor_confirmed_at) {
            return redirect()->route('two-factor.index')->with('status', 'Enable two-factor authentication to access operational data.');
        }

        $lastVerified = (int) $request->session()->get('logs_2fa_passed_at');
        if (!$lastVerified || (time() - $lastVerified) > self::FRESH_WINDOW_SECONDS) {
            return redirect()->route('admin.ops')->with('error', '2FA verification expired.');
        }

        $dir = storage_path('app/' . self::BACKUP_DIR);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $timestamp = date('Y-m-d-His');
        $filename = "umylingo-backup-{$timestamp}.sql";
        $filepath = $dir . '/' . $filename;

        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        Log::info('Starting database backup', [
            'filename' => $filename,
            'user_id' => $user->id,
            'user_username' => $user->username,
            'database' => $database,
            'host' => $host,
            'port' => $port,
        ]);

        try {
            // Try to find mysqldump in common ServBay locations
            $mysqldumpPaths = [
                'C:\ServBay\service\mysql\bin\mysqldump.exe',
                'C:\ServBay\service\mariadb\bin\mysqldump.exe',
                'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
                'C:\Program Files (x86)\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
                'C:\xampp\mysql\bin\mysqldump.exe',
                'C:\wamp64\bin\mysql\mysql8.0.32\bin\mysqldump.exe',
                env('MYSQLDUMP_PATH', ''),
            ];

            $mysqldump = null;
            foreach ($mysqldumpPaths as $path) {
                if (!empty($path) && file_exists($path)) {
                    $mysqldump = $path;
                    break;
                }
            }

            if (!$mysqldump) {
                Log::error('mysqldump not found in any expected location', [
                    'checked_paths' => array_filter($mysqldumpPaths),
                ]);
                return redirect()->route('admin.ops')->with('error', 
                    'mysqldump not found. Set MYSQLDUMP_PATH env var or check https://dev.mysql.com/downloads/mysql/');
            }

            Log::info('Using mysqldump at: ' . $mysqldump, ['filename' => $filename]);

            // Try mysqldump via proc_open for better error handling
            $descriptorspec = array(
                0 => array("pipe", "r"),
                1 => array("pipe", "w"),
                2 => array("pipe", "w"),
            );

            $cmd = sprintf(
                '"%s" -h %s -P %s -u %s -p%s --single-transaction --routines --triggers %s',
                $mysqldump,
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database)
            );

            Log::debug('Executing backup command', ['cmd' => str_replace($password, '****', $cmd)]);

            $process = proc_open($cmd, $descriptorspec, $pipes);

            if (!is_resource($process)) {
                throw new \Exception('Failed to start mysqldump process');
            }

            // Read the output
            $output = stream_get_contents($pipes[1]);
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $returnCode = proc_close($process);

            Log::info('mysqldump process completed', [
                'return_code' => $returnCode,
                'output_size' => strlen($output),
                'error_output' => $error,
            ]);

            if ($returnCode !== 0 || empty($output)) {
                Log::error('Backup command failed', [
                    'return_code' => $returnCode,
                    'error' => $error,
                    'output_size' => strlen($output),
                ]);
                return redirect()->route('admin.ops')->with('error', 'Backup failed: ' . ($error ?: 'No output from mysqldump (return code: ' . $returnCode . ')'));
            }

            // Write the dump to file
            if (file_put_contents($filepath, $output) === false) {
                throw new \Exception('Failed to write backup file to ' . $filepath);
            }

            Log::info('Backup created successfully', [
                'filename' => $filename,
                'size' => filesize($filepath),
                'user_id' => $user->id,
                'user_username' => $user->username,
            ]);

            // Cleanup old backups, keeping only 5 most recent
            $this->pruneOldBackups(5);

            return redirect()->route('admin.ops')->with('success', "Backup created: {$filename}");

        } catch (\Exception $e) {
            Log::error('Backup exception', [
                'filename' => $filename,
                'user_id' => $user->id,
                'user_username' => $user->username,
                'error' => $e->getMessage(),
            ]);

            if (file_exists($filepath)) {
                unlink($filepath);
            }

            return redirect()->route('admin.ops')->with('error', 'Backup error: ' . $e->getMessage());
        }
    }

    private function pruneOldBackups(int $keepCount = 5): void
    {
        $dir = storage_path('app/' . self::BACKUP_DIR);
        if (!is_dir($dir)) {
            return;
        }

        $files = glob($dir . '/*.{sql,sql.gz,zip}', GLOB_BRACE) ?: [];
        if (count($files) <= $keepCount) {
            return;
        }

        // Sort by modification time, newest first
        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));

        // Delete files beyond the keep count
        $filesToDelete = array_slice($files, $keepCount);
        foreach ($filesToDelete as $file) {
            try {
                unlink($file);
                Log::info('Deleted old backup', ['file' => basename($file)]);
            } catch (\Throwable $e) {
                Log::warning('Failed to delete old backup', ['file' => basename($file), 'error' => $e->getMessage()]);
            }
        }
    }

    public function restoreBackup(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_secret || !$user->two_factor_confirmed_at) {
            return redirect()->route('two-factor.index')->with('status', 'Enable two-factor authentication to access operational data.');
        }

        $lastVerified = (int) $request->session()->get('logs_2fa_passed_at');
        if (!$lastVerified || (time() - $lastVerified) > self::FRESH_WINDOW_SECONDS) {
            return redirect()->route('admin.ops')->with('error', '2FA verification expired.');
        }

        $filename = $request->input('filename');
        if (!$filename || !preg_match('/^umylingo-backup-\d{4}-\d{2}-\d{2}-\d{6}\.sql$/', $filename)) {
            return redirect()->route('admin.ops')->with('error', 'Invalid backup filename.');
        }

        $filepath = storage_path('app/' . self::BACKUP_DIR . '/' . $filename);
        if (!file_exists($filepath)) {
            return redirect()->route('admin.ops')->with('error', 'Backup file not found.');
        }

        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $testDb = 'umylingo_test_' . str_replace(['-', ':'], '', date('Y-m-d-His'));

        try {
            Log::info('Starting restore to test database', [
                'backup_file' => $filename,
                'test_database' => $testDb,
                'user_id' => $user->id,
                'user_username' => $user->username,
            ]);

            $mysqlPaths = [
                'C:\ServBay\service\mysql\bin\mysql.exe',
                'C:\ServBay\service\mariadb\bin\mysql.exe',
                'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe',
                'C:\xampp\mysql\bin\mysql.exe',
                env('MYSQL_PATH', ''),
            ];

            $mysql = null;
            foreach ($mysqlPaths as $path) {
                if (!empty($path) && file_exists($path)) {
                    $mysql = $path;
                    break;
                }
            }

            if (!$mysql) {
                Log::error('mysql executable not found for restore', ['checked_paths' => array_filter($mysqlPaths)]);
                return redirect()->route('admin.ops')->with('error', 'mysql executable not found. Set MYSQL_PATH env var.');
            }

            $createDbCmd = sprintf(
                '"%s" -h %s -P %s -u %s -p%s -e "CREATE DATABASE %s;"',
                $mysql,
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($testDb)
            );

            $descriptorspec = array(
                0 => array("pipe", "r"),
                1 => array("pipe", "w"),
                2 => array("pipe", "w"),
            );

            $process = proc_open($createDbCmd, $descriptorspec, $pipes);
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $returnCode = proc_close($process);

            if ($returnCode !== 0) {
                Log::error('Failed to create test database', ['error' => $error, 'db' => $testDb]);
                return redirect()->route('admin.ops')->with('error', 'Failed to create test database: ' . $error);
            }

            Log::info('Test database created', ['database' => $testDb]);

            $restoreCmd = sprintf(
                '"%s" -h %s -P %s -u %s -p%s %s < "%s"',
                $mysql,
                $host,
                $port,
                $username,
                $password,
                $testDb,
                $filepath
            );

            $process = proc_open($restoreCmd, $descriptorspec, $pipes);
            $output = stream_get_contents($pipes[1]);
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $returnCode = proc_close($process);

            if ($returnCode !== 0) {
                $dropCmd = sprintf(
                    '"%s" -h %s -P %s -u %s -p%s -e "DROP DATABASE %s;"',
                    $mysql,
                    $host,
                    $port,
                    $username,
                    $password,
                    escapeshellarg($testDb)
                );
                proc_open($dropCmd, $descriptorspec, $pipes);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);

                Log::error('Restore failed', ['error' => $error, 'db' => $testDb]);
                return redirect()->route('admin.ops')->with('error', 'Restore failed: ' . $error);
            }

            Log::info('Restore completed successfully', [
                'backup_file' => $filename,
                'user_id' => $user->id,
                'user_username' => $user->username,
            ]);

            return redirect()->route('admin.ops')->with('success', 
                "Restore complete! Test DB: {$testDb}. Connect: mysql -h {$host} -u {$username} -p {$testDb}");

        } catch (\Exception $e) {
            Log::error('Restore exception', [
                'filename' => $filename,
                'user_id' => $user->id,
                'user_username' => $user->username,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.ops')->with('error', 'Restore error: ' . $e->getMessage());
        }
    }

    private function recentLogs(): array
    {
        $path = storage_path('logs/laravel.log');
        if (!file_exists($path)) {
            return [];
        }

        $rawLines = array_slice(
            file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES),
            -self::MAX_LOG_LINES
        );

        $parsed = [];
        foreach ($rawLines as $line) {
            $item = $this->parseLine($line);
            if ($item) {
                $parsed[] = $item;
            }
        }

        return array_reverse($parsed);
    }

    private function parseLine(string $line): ?array
    {
        $pattern = '/^\[([^\]]+)\]\s+(\w+)\.(\w+):\s+(.+?)(?:\s+(\{.+\}))?$/s';

        if (!preg_match($pattern, $line, $matches)) {
            $simplePattern = '/^\[([^\]]+)\]\s+(\w+):\s+(.+?)(?:\s+(\{.+\}))?$/s';
            if (!preg_match($simplePattern, $line, $matches)) {
                return null;
            }

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
            $decoded = json_decode($contextJson, true);
            if (is_array($decoded)) {
                $context = $decoded;
            }
        }

        return [
            'timestamp' => $timestamp,
            'channel' => $channel,
            'level' => $level,
            'message' => trim($message),
            'context' => $context,
        ];
    }

    private function listBackups(): array
    {
        $dir = storage_path('app/' . self::BACKUP_DIR);
        if (!is_dir($dir)) {
            return [];
        }

        $files = glob($dir . '/*.{sql,sql.gz,zip}', GLOB_BRACE) ?: [];
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => filesize($file),
                'modified' => filemtime($file),
            ];
        }

        usort($backups, fn($a, $b) => $b['modified'] <=> $a['modified']);

        return $backups;
    }

    private function healthSnapshot(): array
    {
        $results = [];

        try {
            DB::connection()->getPdo();
            $results[] = [
                'name' => 'Database',
                'status' => 'ok',
                'detail' => 'Connected',
            ];
        } catch (\Throwable $e) {
            $results[] = [
                'name' => 'Database',
                'status' => 'fail',
                'detail' => $e->getMessage(),
            ];
        }

        try {
            $count = DB::table('jobs')->count();
            $results[] = [
                'name' => 'Queue (database)',
                'status' => 'ok',
                'detail' => 'Jobs queued: ' . $count,
            ];
        } catch (\Throwable $e) {
            $results[] = [
                'name' => 'Queue (database)',
                'status' => 'warn',
                'detail' => 'Queue table not reachable',
            ];
        }

        try {
            Cache::put('health_ping', '1', 60);
            $cacheWorks = Cache::get('health_ping') === '1';
            $results[] = [
                'name' => 'Cache',
                'status' => $cacheWorks ? 'ok' : 'warn',
                'detail' => $cacheWorks ? 'Cache read/write OK' : 'Cache read/write failed',
            ];
        } catch (\Throwable $e) {
            $results[] = [
                'name' => 'Cache',
                'status' => 'warn',
                'detail' => 'Cache not reachable',
            ];
        }

        return $results;
    }
}
