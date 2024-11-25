<div class="learn-deck-view-page">
    <div class="learn-deck-view-header">
        <h2>Deck: {{ $quizProgress->deck->language }}</h2>
        <p>{{ $quizProgress->deck->deck_description }}</p>
    </div>

    <main class="learn-deck-view-grid">
        <div class="learn-deck-view-card" id="card-container">
            <div class="learn-deck-view-description">
                <p>{{ $currentQuizCard->question }}</p>
                @foreach ($currentQuizCard->choices as $choice)
                    <button>{{ $choice->choice }}</button>
                @endforeach
            </div>
        </div>
    </main>

    <footer class="learn-deck-navigation">
        <button class="learn-btn learn-btn-secondary" wire:click="previousQuizCard"
            {{ $currentIndex == 0 ? 'disabled' : '' }}>
            Previous
        </button>
        <button class="learn-btn learn-btn-secondary" wire:click="nextQuizCard"
            {{ $currentIndex >= $quizProgress->deck->cards->count() - 1 ? 'disabled' : '' }}>
            Next
        </button>
    </footer>
</div>
