<div>
    <h2>Learning Dashboard</h2>
    <div>
        <p><strong>Accuracy:</strong> {{ $accuracy }}%</p>
        <p><strong>Average Response Time:</strong> {{ $avgResponseTime }} seconds</p>
    </div>
    <div>
        <h3>Deck Progress</h3>
        <ul>
            @foreach ($deckProgress as $deck)
                <li>{{ $deck['deck'] }}: {{ round($deck['progress'], 2) }}%</li>
            @endforeach
        </ul>
    </div>

    <canvas id="accuracyChart"></canvas>

    <script>
        const ctx = document.getElementById('accuracyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'], // Example labels
                datasets: [{
                    label: 'Accuracy (%)',
                    data: [70, 75, 80, 85], // Example data
                    borderColor: '#0c5894',
                    backgroundColor: 'rgba(12, 88, 148, 0.2)'
                }]
            }
        });
    </script>

</div>
