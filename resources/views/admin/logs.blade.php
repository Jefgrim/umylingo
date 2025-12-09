<x-layouts.admin title="System Activity Logs">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">System Activity Logs</h1>

        <!-- Filter Tabs -->
        <div class="mb-6 border-b border-gray-300">
            <div class="flex gap-2 overflow-x-auto">
                @foreach($types as $key => $label)
                    <a href="{{ route('admin.logs') }}?type={{ $key }}"
                       class="px-4 py-2 font-medium border-b-2 whitespace-nowrap transition
                              {{ $type === $key 
                                  ? 'border-blue-500 text-blue-600' 
                                  : 'border-transparent text-gray-600 hover:text-gray-900' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Log Source Info -->
        <p class="text-sm text-gray-500 mb-4">Source: <code class="bg-gray-100 px-2 py-1 rounded">{{ $source }}</code></p>

        <!-- Logs Display -->
        @if(empty($logs))
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded">
                No log entries found for this filter.
            </div>
        @else
            <div class="space-y-3 max-h-screen overflow-y-auto">
                @foreach($logs as $log)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white hover:shadow-md transition"
                         style="border-left: 4px solid {{ $levelColor($log['level']) }}">
                        
                        <!-- Header: Timestamp and Level -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-mono text-gray-600">{{ $log['timestamp'] }}</span>
                            <span class="inline-block px-3 py-1 rounded text-xs font-bold text-white"
                                  style="background-color: {{ $levelColor($log['level']) }}">
                                {{ strtoupper($log['level']) }}
                            </span>
                        </div>

                        <!-- Message -->
                        <p class="text-sm font-semibold text-gray-800 mb-2">{{ $log['message'] }}</p>

                        <!-- Context Data (if present) -->
                        @if(!empty($log['context']))
                            <div class="bg-gray-50 p-3 rounded text-xs font-mono text-gray-700 overflow-auto max-h-32 mt-2">
                                <strong>Context:</strong>
                                @foreach($log['context'] as $key => $value)
                                    <div><span class="text-blue-600">{{ $key }}</span>: 
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

            <p class="text-sm text-gray-500 mt-4">
                Showing {{ count($logs) }} log entries
                @if($type !== 'all')
                    (filtered by <strong>{{ $types[$type] ?? $type }}</strong>)
                @endif
            </p>
        @endif
    </div>
</x-layouts.admin>
