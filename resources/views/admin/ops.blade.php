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

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="ops-grid">
        <section class="ops-card">
            <div class="ops-card-header">
                <h2>Health</h2>
                <p class="muted">Live checks for core services</p>
            </div>
            <div class="health-list">
                @forelse($health as $check)
                    <div class="health-item">
                        <span class="health-name">{{ $check['name'] }}</span>
                        <span class="health-chip {{ $check['status'] }}">{{ strtoupper($check['status']) }}</span>
                        <p class="health-detail">{{ $check['detail'] }}</p>
                    </div>
                @empty
                    <p class="muted">No health data.</p>
                @endforelse
            </div>
        </section>

        <section class="ops-card">
            <div class="ops-card-header">
                <div>
                    <h2>Backups</h2>
                    <p class="muted">Listing files in storage/app/backups</p>
                </div>
                <form method="POST" action="{{ route('admin.ops.backup') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-backup">
                        <span>💾</span>
                        Create Backup Now
                    </button>
                </form>
            </div>
            @if(empty($backups))
                <p class="muted">No backup files found.</p>
            @else
                <div class="table">
                    <div class="table-head">
                        <span>Name</span>
                        <span>Size</span>
                        <span>Modified</span>
                    </div>
                    @foreach($backups as $backup)
                        <div class="table-row">
                            <span class="truncate">{{ $backup['name'] }}</span>
                            <span>{{ $formatSize($backup['size']) }}</span>
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <span>{{ $formatTime($backup['modified']) }}</span>
                                <form method="POST" action="{{ route('admin.ops.restore') }}" style="margin: 0; display: inline;">
                                    @csrf
                                    <input type="hidden" name="filename" value="{{ $backup['name'] }}">
                                    <button type="submit" class="btn-restore" onclick="return confirm('Restore to test database? This will create a new test DB.');">↻ Test</button>
                                </form>
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="ops-card logs-card">
            <div class="ops-card-header">
                <h2>Recent Runtime Logs</h2>
                <p class="muted">Last {{ count($logs) }} entries from storage/logs/laravel.log</p>
            </div>
            @if(empty($logs))
                <p class="muted">No log entries available.</p>
            @else
                <div class="logs-list">
                    @foreach($logs as $log)
                        <div class="log-item">
                            <div class="log-meta">
                                <span class="log-time">{{ $log['timestamp'] }}</span>
                                <span class="log-level level-{{ $log['level'] }}">{{ strtoupper($log['level']) }}</span>
                            </div>
                            <p class="log-message">{{ $log['message'] }}</p>
                            @if(!empty($log['context']))
                                <pre class="log-context">{{ json_encode($log['context'], JSON_PRETTY_PRINT) }}</pre>
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
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f1aeb5;
            color: #ad3324;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-weight: 600;
        }
        .ops-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
        }
        .ops-card {
            background: #fdfbfb;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }
        .ops-card-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .ops-card-header h2 { margin: 0 0 0.2rem 0; color: #0c5894; }
        .ops-card-header .muted { margin: 0; color: #6b7280; }
        .btn-backup {
            background: #16a34a;
            color: white;
            border: none;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
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
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-restore:hover {
            background: #094270;
            transform: translateY(-1px);
        }
        .health-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .health-item { border: 1px solid #e5e7eb; border-radius: 10px; padding: 0.75rem; background: #fff; }
        .health-name { font-weight: 700; color: #0c5894; }
        .health-chip { padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.75rem; font-weight: 700; color: #fff; margin-left: 0.5rem; }
        .health-chip.ok { background: #16a34a; }
        .health-chip.warn { background: #f97316; }
        .health-chip.fail { background: #dc2626; }
        .health-detail { margin: 0.35rem 0 0; color: #374151; }
        .table { display: flex; flex-direction: column; gap: 0.35rem; }
        .table-head, .table-row { display: grid; grid-template-columns: 2fr 0.8fr 1.2fr; gap: 0.5rem; align-items: center; }
        .table-head { font-weight: 700; color: #0c5894; }
        .table-row { padding: 0.65rem; border: 1px solid #e5e7eb; border-radius: 10px; background: #fff; }
        .truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .logs-card { grid-column: 1 / -1; }
        .logs-list { display: flex; flex-direction: column; gap: 0.75rem; max-height: 520px; overflow: auto; }
        .log-item { border: 1px solid #e5e7eb; border-radius: 10px; padding: 0.75rem; background: #fff; }
        .log-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem; }
        .log-time { color: #6b7280; font-size: 0.9rem; }
        .log-level { padding: 0.2rem 0.5rem; border-radius: 6px; color: #fff; font-weight: 700; font-size: 0.8rem; }
        .log-level.level-error, .log-level.level-critical, .log-level.level-alert, .log-level.level-emergency { background: #dc2626; }
        .log-level.level-warning { background: #f59e0b; }
        .log-level.level-info { background: #2563eb; }
        .log-level.level-debug, .log-level.level-notice { background: #6b21a8; }
        .log-message { margin: 0 0 0.35rem 0; color: #111827; }
        .log-context { background: #f3f4f6; border-radius: 8px; padding: 0.65rem; margin: 0; font-size: 0.9rem; overflow-x: auto; }
        .muted { color: #6b7280; }
        @media (max-width: 768px) {
            .table-head, .table-row { grid-template-columns: 1.5fr 0.6fr 1fr; }
            .logs-card { grid-column: auto; }
        }
    </style>
</x-layouts.admin>
