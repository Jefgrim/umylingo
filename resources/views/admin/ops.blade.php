<x-layouts.admin title="Operations Center">
    @php
        $formatSize = function ($bytes) {
            if ($bytes <= 0) {
                return '0 B';
            }
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $exp = (int) floor(log($bytes, 1024));
            return round($bytes / pow(1024, $exp), 2) . ' ' . $units[$exp];
        };
        $formatTime = function ($timestamp) {
            try {
                return \Carbon\Carbon::createFromTimestamp($timestamp)->diffForHumans();
            } catch (\Throwable $e) {
                return date('Y-m-d H:i:s', $timestamp);
            }
        };
    @endphp

    <div class="logs-container">
        <h1 class="dashboard-title">Operations Center</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @php
            $backupCount = count($backups ?? []);
            $latestBackup = $backupCount > 0 ? $backups[0] : null;
        @endphp

        <div class="summary-grid">
            <div class="summary-card">
                <p class="summary-label">Backups stored</p>
                <p class="summary-value">{{ $backupCount }}</p>
                <p class="summary-hint">Retention: last 5 kept</p>
            </div>
            <div class="summary-card">
                <p class="summary-label">Last backup size</p>
                <p class="summary-value">{{ $latestBackup ? $formatSize($latestBackup['size']) : '—' }}</p>
                <p class="summary-hint">{{ $latestBackup ? $formatTime($latestBackup['modified']) : 'No backups yet' }}</p>
            </div>
            <div class="summary-card">
                <p class="summary-label">Backup location</p>
                <p class="summary-value">storage/app/backups</p>
                <p class="summary-hint">Excluded from git</p>
            </div>
        </div>

        <section class="ops-card">
            <div class="ops-card-header">
                <div>
                    <h2>Backups</h2>
                    <p class="muted">Listing files in storage/app/backups</p>
                </div>
                <form method="POST" action="{{ route('admin.ops.backup') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-backup">Create Backup Now</button>
                </form>
            </div>
            @if(empty($backups))
                <div class="logs-empty-state">
                    <p>No backup files found.</p>
                </div>
            @else
                <div class="logs-list">
                    @foreach($backups as $backup)
                        <div class="log-entry" style="border-left-color: #0c5894;">
                            <div class="log-entry-header">
                                <div class="backup-info">
                                    <span class="log-timestamp">{{ $backup['name'] }}</span>
                                    <span class="backup-meta">{{ $formatSize($backup['size']) }} • {{ $formatTime($backup['modified']) }}</span>
                                </div>
                                <form method="POST" action="{{ route('admin.ops.restore') }}" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="filename" value="{{ $backup['name'] }}">
                                    <button type="submit" class="btn-restore" onclick="return confirm('Restore to test database? This will create a new test DB.');">↻ Test Restore</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="ops-card" style="margin-top: 2rem;">
            <div class="ops-card-header">
                <h2>Recent Runtime Logs</h2>
                <p class="muted">Last {{ count($logs) }} entries from storage/logs/laravel.log</p>
            </div>
            @if(empty($logs))
                <div class="logs-empty-state">
                    <p>No log entries available.</p>
                </div>
            @else
                <div class="logs-list">
                    @foreach($logs as $log)
                        <div class="log-entry" style="border-left-color: {{ 
                            in_array($log['level'], ['error', 'critical', 'alert', 'emergency']) ? '#dc2626' : 
                            ($log['level'] === 'warning' ? '#f59e0b' : 
                            ($log['level'] === 'info' ? '#2563eb' : '#6b21a8'))
                        }}">
                            <div class="log-entry-header">
                                <span class="log-timestamp">{{ $log['timestamp'] }}</span>
                                <span class="log-level" style="background-color: {{ 
                                    in_array($log['level'], ['error', 'critical', 'alert', 'emergency']) ? '#dc2626' : 
                                    ($log['level'] === 'warning' ? '#f59e0b' : 
                                    ($log['level'] === 'info' ? '#2563eb' : '#6b21a8'))
                                }}">{{ strtoupper($log['level']) }}</span>
                            </div>
                            <p class="log-message">{{ $log['message'] }}</p>
                            @if(!empty($log['context']))
                                <div class="log-context">
                                    <strong>Context:</strong>
                                    @foreach($log['context'] as $key => $value)
                                        <div class="log-context-item">
                                            <span class="log-context-key">{{ $key }}</span>: 
                                            @if(is_array($value))
                                                {{ json_encode($value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <style>
        .alert-success {
            background: #d1e7dd;
            border: 1px solid #a3cfbb;
            color: #0f5132;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f1aeb5;
            color: #ad3324;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .summary-card {
            background: #0c5894;
            color: white;
            border-radius: 8px;
            padding: 1.1rem 1.3rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            min-width: 0;
        }
        .summary-label { margin: 0; opacity: 0.85; font-weight: 600; font-size: 0.9rem; }
        .summary-value { margin: 0.2rem 0 0.1rem; font-size: 1.6rem; font-weight: 800; word-wrap: break-word; }
        .summary-hint { margin: 0; opacity: 0.85; font-size: 0.95rem; word-wrap: break-word; }
        .ops-card {
            background: #fdfbfb;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            min-width: 0;
        }
        .ops-card-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            gap: 1rem; 
            flex-wrap: wrap; 
            margin-bottom: 1.5rem; 
        }
        .ops-card-header h2 { margin: 0 0 0.3rem 0; color: #0c5894; font-size: 1.5rem; font-weight: 700; }
        .ops-card-header .muted { margin: 0; color: #666; font-size: 0.95rem; }
        .btn-backup {
            background: #16a34a;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        .btn-backup:hover {
            background: #15803d;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3);
        }
        .btn-restore {
            background: #0c5894;
            color: white;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-restore:hover {
            background: #094270;
            transform: translateY(-1px);
        }
        .backup-info {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        .backup-meta {
            font-size: 0.85rem;
            color: #666;
            font-weight: 500;
        }
        .muted { color: #666; }
        
        @media (max-width: 768px) {
            .summary-grid { grid-template-columns: 1fr; gap: 0.85rem; }
            .summary-card { padding: 0.9rem 1.1rem; }
            .summary-value { font-size: 1.4rem; }
            .ops-card { padding: 1rem; }
            .ops-card-header { flex-direction: column; align-items: stretch; gap: 0.75rem; }
            .btn-backup { justify-content: center; width: 100%; }
            .btn-restore { width: 100%; }
        }
    </style>
</x-layouts.admin>
