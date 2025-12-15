<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const currentYear = "{{ now()->year }}";

    const ctx = document.getElementById('usersChart').getContext('2d');
    const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const data = {
        labels: labels,
        datasets: [{
            label: `${currentYear} Umylingo Users Count`,
            data: [{{ $currentUsersPerMonth[1] }}, {{ $currentUsersPerMonth[2] }}, {{ $currentUsersPerMonth[3] }}, {{ $currentUsersPerMonth[4] }}, {{ $currentUsersPerMonth[5] }}, {{ $currentUsersPerMonth[6] }}, {{ $currentUsersPerMonth[7] }}, {{ $currentUsersPerMonth[8] }}, {{ $currentUsersPerMonth[9] }}, {{ $currentUsersPerMonth[10] }}, {{ $currentUsersPerMonth[11] }}, {{ $currentUsersPerMonth[12] }}],
            fill: false,
            borderColor: '#0c5894',
            tension: 0.1
        }]
    };
    new Chart(ctx, { type: 'line', data });

    const pieCtx = document.getElementById('quizPieChart').getContext('2d');
    const quizData = {
        labels: ['Started Quizzes', 'Completed Quizzes'],
        datasets: [{
            data: [{{ $totalQuizzesStarted }}, {{ $totalQuizzesCompleted }}],
            backgroundColor: ['#0c5894', '#ad3324'],
            hoverBackgroundColor: ['#0c5894', '#ad3324']
        }]
    };
    new Chart(pieCtx, { type: 'pie', data: quizData });

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

    document.getElementById('deckModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeckModal();
    });
</script>
