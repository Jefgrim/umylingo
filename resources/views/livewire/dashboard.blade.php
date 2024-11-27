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
    </div>

    <div class="charts-container">
        <div class="chart-box">
            <canvas id="usersChart"></canvas>
        </div>
        <div class="chart-box">
            <canvas id="quizPieChart"></canvas>
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
</script>
