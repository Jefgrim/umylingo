<div class="dashboard">
    <h1 class="dashboard-title">Admin Dashboard</h1>
    <div class="stats-container">
        <div class="stat-box">
            <p class="stat-title">Total Users</p>
            <p class="stat-value">{{ $currentUsers }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-title">Total Decks</p>
            <p class="stat-value">{{ $totalDecks }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-title">Total Cards</p>
            <p class="stat-value">{{ $totalCards }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-title">Total Quizzes Started</p>
            <p class="stat-value">{{ $totalQuizzesStarted }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-title">Total Quizzes Completed</p>
            <p class="stat-value">{{ $totalQuizzesCompleted }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-title">Daily Active Users</p>
            <p class="stat-value">{{ $dau }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-title">Weekly Active Users</p>
            <p class="stat-value">{{ $wau }}</p>
        </div>
        <div class="stat-box">
            <p class="stat-title">Monthly Active Users</p>
            <p class="stat-value">{{ $mau }}</p>
        </div>
    </div>

    <div class="charts-container">
        <div class="chart-box">
            <canvas id="usersChart"></canvas>
        </div>
        <div class="chart-box">
            <canvas id="quizPieChart"></canvas>
        </div>
        <div class="chart-box">
            <canvas id="funnelChart"></canvas>
        </div>
        <div class="chart-box">
            <canvas id="retentionChart"></canvas>
        </div>
    </div>

    <div class="tables-container" style="margin-top: 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <div class="table-card" style="background: linear-gradient(135deg, #f8fafb 0%, #f0f4f8 100%); border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 1.5rem 0; color: #0c5894; font-size: 1.1rem; font-weight: 600;">✓ Top Performing Decks</h3>
            @forelse($deckTop as $d)
                <button onclick="openDeckModal('{{ $d->deck_id }}', '{{ $d->language }}', '{{ addslashes($d->deck_description) }}', {{ $d->accuracy }}, {{ $d->attempts }})" style="width: 100%; margin-bottom: 1.2rem; padding: 1rem; padding-bottom: 1.2rem; border: 2px solid #e5e7eb; border-radius: 6px; background: white; cursor: pointer; text-align: left; transition: all 0.2s ease; display: block;" onmouseover="this.style.borderColor='#10b981'; this.style.boxShadow='0 4px 12px rgba(16,185,129,0.15)';" onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <span style="font-weight: 600; color: #1f2937; flex: 1; word-break: break-word;">{{ $d->language }} — {{ \Illuminate\Support\Str::limit($d->deck_description, 35) }}</span>
                        <span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 600; white-space: nowrap; margin-left: 0.5rem;">{{ number_format($d->accuracy * 100, 1) }}%</span>
                    </div>
                    <div style="background: #e5e7eb; height: 4px; border-radius: 2px; overflow: hidden;">
                        <div style="height: 100%; background: linear-gradient(90deg, #10b981, #059669); width: {{ min($d->accuracy * 100, 100) }}%;"></div>
                    </div>
                    <small style="color: #6b7280; font-size: 0.8rem;">{{ $d->attempts }} answers</small>
                </button>
            @empty
                <p style="text-align: center; color: #9ca3af; padding: 2rem;">No data yet</p>
            @endforelse
        </div>

        <div class="table-card" style="background: linear-gradient(135deg, #fef8f8 0%, #fef0f0 100%); border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 1.5rem 0; color: #dc2626; font-size: 1.1rem; font-weight: 600;">⚠ Decks Needing Attention</h3>
            @forelse($deckBottom as $d)
                <button onclick="openDeckModal('{{ $d->deck_id }}', '{{ $d->language }}', '{{ addslashes($d->deck_description) }}', {{ $d->accuracy }}, {{ $d->attempts }})" style="width: 100%; margin-bottom: 1.2rem; padding: 1rem; padding-bottom: 1.2rem; border: 2px solid #fee2e2; border-radius: 6px; background: white; cursor: pointer; text-align: left; transition: all 0.2s ease; display: block;" onmouseover="this.style.borderColor='#ef4444'; this.style.boxShadow='0 4px 12px rgba(239,68,68,0.15)';" onmouseout="this.style.borderColor='#fee2e2'; this.style.boxShadow='none';">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <span style="font-weight: 600; color: #1f2937; flex: 1; word-break: break-word;">{{ $d->language }} — {{ \Illuminate\Support\Str::limit($d->deck_description, 35) }}</span>
                        <span style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 600; white-space: nowrap; margin-left: 0.5rem;">{{ number_format($d->accuracy * 100, 1) }}%</span>
                    </div>
                    <div style="background: #fee2e2; height: 4px; border-radius: 2px; overflow: hidden;">
                        <div style="height: 100%; background: linear-gradient(90deg, #ef4444, #dc2626); width: {{ min($d->accuracy * 100, 100) }}%;"></div>
                    </div>
                    <small style="color: #6b7280; font-size: 0.8rem;">{{ $d->attempts }} answers</small>
                </button>
            @empty
                <p style="text-align: center; color: #9ca3af; padding: 2rem;">No data yet</p>
            @endforelse
        </div>
    </div>

    <!-- Deck Details Modal -->
    <div id="deckModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 2rem; max-width: 500px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                <div>
                    <h2 id="modalDeckLang" style="margin: 0 0 0.5rem 0; color: #0c5894; font-size: 1.3rem;"></h2>
                    <p id="modalDeckDesc" style="margin: 0; color: #4b5563; font-size: 0.95rem; line-height: 1.5;"></p>
                </div>
                <button onclick="closeDeckModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6b7280; padding: 0; width: 30px; height: 30px;">✕</button>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #6b7280; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Accuracy</p>
                    <p id="modalAccuracy" style="margin: 0; color: #0c5894; font-size: 1.8rem; font-weight: 700;"></p>
                </div>
                <div>
                    <p style="margin: 0 0 0.5rem 0; color: #6b7280; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Total Answers</p>
                    <p id="modalAnswers" style="margin: 0; color: #0c5894; font-size: 1.8rem; font-weight: 700;"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Get the current year from PHP
    const currentYear = "{{ now()->year }}";

    // Line Chart for Users
    const ctx = document.getElementById('usersChart').getContext('2d');
    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
        'November', 'December'
    ];

    const data = {
        labels: labels,
        datasets: [{
            label: `${currentYear} Umylingo Users Count`, // Use dynamic year here
            data: [{{ $currentUsersPerMonth[1] }}, {{ $currentUsersPerMonth[2] }}, {{ $currentUsersPerMonth[3] }}, {{ $currentUsersPerMonth[4] }}, {{ $currentUsersPerMonth[5] }}, {{ $currentUsersPerMonth[6] }}, {{ $currentUsersPerMonth[7] }}, {{ $currentUsersPerMonth[8] }}, {{ $currentUsersPerMonth[9] }}, {{ $currentUsersPerMonth[10] }}, {{ $currentUsersPerMonth[11] }}, {{ $currentUsersPerMonth[12] }},],
            fill: false,
            borderColor: '#0c5894',
            tension: 0.1
        }]
    };
    const config = {
        type: 'line',
        data: data,
    };
    new Chart(ctx, config);

    // Pie Chart for Quizzes
    const pieCtx = document.getElementById('quizPieChart').getContext('2d');
    const quizData = {
        labels: ['Started Quizzes', 'Completed Quizzes'],
        datasets: [{
            data: [{{ $totalQuizzesStarted }}, {{ $totalQuizzesCompleted }}],
            backgroundColor: ['#0c5894', '#ad3324'],
            hoverBackgroundColor: ['#0c5894', '#ad3324']
        }]
    };
    const pieConfig = {
        type: 'pie',
        data: quizData,
    };
    new Chart(pieCtx, pieConfig);

    // Funnel (Activation)
    const funnelCtx = document.getElementById('funnelChart').getContext('2d');
    const funnelData = {
        labels: ['Registered', 'Learn Started', 'Quiz Started', 'Quiz Completed'],
        datasets: [{
            label: 'Activation Funnel',
            data: [
                {{ $funnel['registered'] ?? 0 }},
                {{ $funnel['learn_started'] ?? 0 }},
                {{ $funnel['quiz_started'] ?? 0 }},
                {{ $funnel['quiz_completed'] ?? 0 }}
            ],
            backgroundColor: ['#0c5894','#0a4a7c','#083a61','#062c49']
        }]
    };
    new Chart(funnelCtx, { type: 'bar', data: funnelData, options: { plugins: { legend: { display: false } } } });

    // Retention Chart (last 6 cohorts)
    const retentionCtx = document.getElementById('retentionChart').getContext('2d');
    const retentionLabels = [
        @foreach($retention as $r)
            '{{ $r['label'] }}',
        @endforeach
    ];
    const retentionRates = [
        @foreach($retention as $r)
            {{ $r['rate'] }},
        @endforeach
    ];
    new Chart(retentionCtx, {
        type: 'line',
        data: { labels: retentionLabels, datasets: [{ label: '30-day Retention %', data: retentionRates, borderColor: '#ad3324', fill: false }]},
        options: { scales: { y: { beginAtZero: true, max: 100 } } }
    });

    // Modal Functions
    function openDeckModal(deckId, language, description, accuracy, answers) {
        document.getElementById('modalDeckLang').textContent = language;
        document.getElementById('modalDeckDesc').textContent = description;
        document.getElementById('modalAccuracy').textContent = (accuracy * 100).toFixed(1) + '%';
        document.getElementById('modalAnswers').textContent = answers;
        document.getElementById('deckModal').style.display = 'flex';
    }

    function closeDeckModal() {
        document.getElementById('deckModal').style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('deckModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeckModal();
    });
</script>
