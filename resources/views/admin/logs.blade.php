<x-layouts.admin title="System Activity Logs">
    <div class="logs-container">
        <h1 class="dashboard-title">System Activity Logs</h1>

        <!-- Date/Time/IP Filter Form -->
        <div class="logs-filter-section">
            <form method="GET" action="{{ route('admin.logs') }}" class="logs-date-filter">
                <div class="date-filter-inputs">
                    <div class="date-input-group">
                        <label for="date_from">From Date:</label>
                        <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}" class="date-input">
                    </div>
                    <div class="date-input-group">
                        <label for="time_from">Time:</label>
                        <input type="time" id="time_from" name="time_from" value="{{ $timeFrom }}" class="date-input">
                    </div>
                    <div class="date-input-group">
                        <label for="date_to">To Date:</label>
                        <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}" class="date-input">
                    </div>
                    <div class="date-input-group">
                        <label for="time_to">Time:</label>
                        <input type="time" id="time_to" name="time_to" value="{{ $timeTo }}" class="date-input">
                    </div>
                    <div class="date-input-group">
                        <label for="ip_filter">IP Address:</label>
                        <input type="text" id="ip_filter" name="ip_filter" value="{{ $ipFilter }}" class="date-input" placeholder="e.g. 192.168.">
                    </div>
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="btn-filter">Filter</button>
                    <a href="{{ route('admin.logs') }}?type={{ $type }}" class="btn-clear">Clear</a>
                </div>
            </form>
        </div>

        <!-- Filter Tabs -->
        <div class="logs-filter-section">
            <div class="logs-filter-tabs">
                @foreach($types as $key => $label)
                    <a href="{{ route('admin.logs') }}?type={{ $key }}{{ $dateFrom ? '&date_from='.$dateFrom : '' }}{{ $dateTo ? '&date_to='.$dateTo : '' }}{{ $timeFrom ? '&time_from='.$timeFrom : '' }}{{ $timeTo ? '&time_to='.$timeTo : '' }}{{ $ipFilter ? '&ip_filter='.$ipFilter : '' }}"
                       class="logs-filter-tab {{ $type === $key ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Log Source Info -->
        <div class="logs-info-box">
            <p class="logs-info-text">Source: <code class="logs-source-code">{{ $source }}</code></p>
        </div>

        <!-- Logs Display -->
        @if(empty($logs))
            <div class="logs-empty-state">
                <p>No log entries found for this filter.</p>
            </div>
        @else
            <div class="logs-list">
                @foreach($logs as $log)
                    <div class="log-entry" style="border-left-color: {{ $levelColor($log['level']) }}">
                        
                        <!-- Header: Timestamp and Level -->
                        <div class="log-entry-header">
                            <span class="log-timestamp">{{ $log['timestamp'] }}</span>
                            <span class="log-level" style="background-color: {{ $levelColor($log['level']) }}">
                                {{ strtoupper($log['level']) }}
                            </span>
                        </div>

                        <!-- Message -->
                        <p class="log-message">{{ $log['message'] }}</p>

                        <!-- Context Data (if present) -->
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

            <p class="logs-footer-text">
                Showing {{ count($logs) }} log entries
                @if($type !== 'all')
                    (filtered by <strong>{{ $types[$type] ?? $type }}</strong>)
                @endif
            </p>
        @endif
    </div>
</x-layouts.admin>
