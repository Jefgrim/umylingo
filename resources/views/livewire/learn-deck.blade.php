<div class="learn-deck-view-page">
    <div class="learn-deck-view-header">
        <h2>Deck: {{ $deckProgress->deck->language }}</h2>
        <p>{{ $deckProgress->deck->deck_description }}</p>
    </div>

    @if ($deckProgress->isLearningCompleted)
        <section class="learn-completion-message">
            <h3>Congratulations!</h3>
            <p>You have completed this deck.</p>
            <div class="learn-completion-actions">
                @if (!$deckProgress->isQuizStarted && !$deckProgress->isQuizCompleted)
                    <button class="learn-btn learn-btn-primary">Start Quiz</button>
                @elseif($deckProgress->isQuizStarted && !$deckProgress->isQuizCompleted)
                    <button class="learn-btn learn-btn-primary">Continue Quiz</button>
                @elseif($deckProgress->isQuizCompleted)
                    <button class="learn-btn learn-btn-primary">Review Quiz</button>
                @endif
            </div>
        </section>
    @endif

    <main class="learn-deck-view-grid">
        <div class="learn-deck-view-card" id="card-container">
            <div class="learn-deck-view-description">
                <p>{{ $currentCard->content }}</p>
            </div>
        </div>
    </main>

    <footer class="learn-deck-navigation">
        <button class="learn-btn learn-btn-secondary" wire:click="previousCard"
            {{ $currentIndex == 0 ? 'disabled' : '' }}>
            Previous
        </button>
        <button class="learn-btn learn-btn-secondary" wire:click="nextCard"
            {{ $currentIndex >= $deckProgress->deck->cards->count() - 1 ? 'disabled' : '' }}>
            Next
        </button>
    </footer>
</div>
