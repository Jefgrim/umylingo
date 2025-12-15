<div class="dashboard-section">
    <h2 class="section-heading">Comprehensive Analytics</h2>

    <div class="metric-group">
        <h3 class="metric-title metric-blue">User Engagement</h3>
        <div class="metric-grid metric-grid-4">
            <div class="metric-card metric-card-blue">
                <p class="metric-label">Quiz Completion Rate</p>
                <p class="metric-value">{{ $quizCompletionRate }}%</p>
            </div>
            <div class="metric-card metric-card-blue">
                <p class="metric-label">Avg Session (mins)</p>
                <p class="metric-value">{{ $avgSessionDuration }}</p>
            </div>
            <div class="metric-card metric-card-blue">
                <p class="metric-label">Avg Learning Streak</p>
                <p class="metric-value">{{ $learningStreakAvg }} days</p>
            </div>
            <div class="metric-card metric-card-blue">
                <p class="metric-label">Cards Mastered</p>
                <p class="metric-value">{{ $cardsMastered }}</p>
            </div>
        </div>
    </div>

    <div class="metric-group">
        <h3 class="metric-title metric-green">Learning Performance</h3>
        <div class="metric-grid metric-grid-4">
            <div class="metric-card metric-card-green">
                <p class="metric-label">Avg Quiz Score</p>
                <p class="metric-value">{{ $avgQuizScore }}%</p>
            </div>
            <div class="metric-card metric-card-green">
                <p class="metric-label">Deck Completion (mins)</p>
                <p class="metric-value">{{ $timeToDeckCompletion }}</p>
            </div>
            <div class="metric-card metric-card-green">
                <p class="metric-label">Learning Velocity (Weekly)</p>
                <p class="metric-value">{{ $learningVelocity }}</p>
                <p class="metric-sub">cards per user</p>
            </div>
            <div class="metric-card metric-card-green">
                <p class="metric-label">Card Error Rate</p>
                <p class="metric-value">{{ $cardErrorRate }}%</p>
            </div>
        </div>
    </div>

    <div class="metric-group">
        <h3 class="metric-title metric-amber">Content Insights</h3>
        <div class="metric-grid metric-grid-3">
            <div class="metric-card metric-card-amber">
                <p class="metric-label">Deck Completion Rate</p>
                <p class="metric-value">{{ $deckCompletionRate }}%</p>
            </div>
            <div class="metric-card metric-card-amber">
                <p class="metric-label">Active Decks (30d)</p>
                <p class="metric-value">{{ $contentEngagement }}%</p>
            </div>
            <div class="metric-card metric-card-amber">
                <p class="metric-label">Most Challenging</p>
                @if(count($mostChallengingCards) > 0)
                    <p class="metric-text">{{ $mostChallengingCards[0]['word'] }} ({{ $mostChallengingCards[0]['accuracy'] }}%)</p>
                @else
                    <p class="metric-text">N/A</p>
                @endif
            </div>
        </div>
    </div>

    <div class="metric-group">
        <h3 class="metric-title metric-red">User Health</h3>
        <div class="metric-grid metric-grid-4">
            <div class="metric-card metric-card-red">
                <p class="metric-label">Churn Rate (30d)</p>
                <p class="metric-value">{{ $churnRate }}%</p>
            </div>
            <div class="metric-card metric-card-red">
                <p class="metric-label">New User Engagement</p>
                <p class="metric-value">{{ $newUserQuality }}%</p>
            </div>
            <div class="metric-card metric-card-red">
                <p class="metric-label">Time to First Quiz</p>
                <p class="metric-value">{{ $timeToFirstQuiz }} min</p>
            </div>
            <div class="metric-card metric-card-red">
                <p class="metric-label">Feature Adoption</p>
                <p class="metric-text">Quiz: {{ $featureAdoption['quiz_mode'] }}% | Learn: {{ $featureAdoption['learn_mode'] }}%</p>
            </div>
        </div>
    </div>

    <div class="metric-group">
        <h3 class="metric-title metric-purple">Growth</h3>
        <div class="metric-grid metric-grid-2">
            <div class="metric-card metric-card-purple">
                <p class="metric-label">Week-over-Week Growth</p>
                <p class="metric-value">{{ $weekOverWeekGrowth }}%</p>
            </div>
            <div class="metric-card metric-card-purple">
                <p class="metric-label">User Segmentation</p>
                <p class="metric-text">Active: {{ $userSegmentation['active']['count'] }} ({{ $userSegmentation['active']['percent'] }}%) | Inactive: {{ $userSegmentation['inactive']['count'] }} ({{ $userSegmentation['inactive']['percent'] }}%)</p>
            </div>
        </div>
    </div>

    <div class="metric-group">
        <h3 class="metric-title metric-cyan">Advanced Insights</h3>
        <div class="metric-grid metric-grid-3">
            <div class="metric-card metric-card-cyan">
                <p class="metric-label">First Attempt Success</p>
                <p class="metric-value">{{ $firstAttemptSuccess }}%</p>
                <p class="metric-sub">Cards correct on first try</p>
            </div>
            <div class="metric-card metric-card-cyan">
                <p class="metric-label">Review Compliance</p>
                <p class="metric-value">{{ $reviewCompliance }}%</p>
                <p class="metric-sub">Users reviewing cards</p>
            </div>
            <div class="metric-card metric-card-cyan">
                <p class="metric-label">Deck Popularity Trend</p>
                @if(count($deckPopularityTrend) > 0)
                    <p class="metric-text strong">{{ $deckPopularityTrend[0]['deck'] }}</p>
                    <p class="metric-trend" style="color: {{ $deckPopularityTrend[0]['change'] >= 0 ? '#10b981' : '#ef4444' }};">{{ $deckPopularityTrend[0]['change'] > 0 ? '+' : '' }}{{ $deckPopularityTrend[0]['change'] }}%</p>
                    <p class="metric-sub">vs previous week</p>
                @else
                    <p class="metric-text">N/A</p>
                @endif
            </div>
        </div>
    </div>
</div>
