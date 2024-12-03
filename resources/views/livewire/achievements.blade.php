<div class="achievements-container">

    @foreach ($achievements as $achievement)
        @if (!$achievement->achieved_at)
            <div class="achievement-card not-achieved">
                <div class="achievement-header">
                    <h3 class="achievement-title">{{ $achievement->achievement->achievement_title }}</h3>
                    <div class="badge not-achieved-badge">Not Achieved</div>
                </div>
                <p class="achievement-description">{{ $achievement->achievement->achievement_description }}</p>
                <div class="achievement-requirements">
                    <strong>Requirements:</strong>
                    <ul>
                        <li>{{ $achievement->achievement->achievement_requirements }}</li>
                    </ul>
                </div>
                <div class="achievement-status">Keep Going!</div>
            </div>
        @else
            <div class="achievement-card achieved">
                <div class="achievement-header">
                    <h3 class="achievement-title">{{ $achievement->achievement->achievement_title }}</h3>
                    <div class="badge achieved-badge">Achieved</div>
                </div>
                <p class="achievement-description">{{ $achievement->achievement->achievement_description }}</p>
                <div class="achievement-details">
                    <strong>Achieved At:</strong> {{ $achievement->achieved_at }}
                </div>
            </div>
        @endif
    @endforeach



    <!-- Add more cards as needed -->
</div>
