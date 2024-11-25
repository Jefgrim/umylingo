<div class="learn-deck-view-page">
    <div class="learn-deck-view-header">
        <h2>Deck: {{ $learnProgress->deck->language }}</h2>
        <p>{{ $learnProgress->deck->deck_description }}</p>
    </div>

    @if ($learnProgress->isCompleted)
        <section class="learn-completion-message">
            <h3>Congratulations!</h3>
            <p>You have completed learning this deck.</p>
            <div class="learn-completion-actions">
                @if (!$quizProgress->isStarted && !$quizProgress->isCompleted)
                    <a href="/deck/{{ $learnProgress->quizProgress->id }}/quiz" class="learn-btn learn-btn-primary">Start
                        Quiz</a>
                @elseif($quizProgress->isStarted && !$quizProgress->isCompleted)
                    <a href="/deck/{{ $learnProgress->quizProgress->id }}/quiz"
                        class="learn-btn learn-btn-primary">Continue Quiz</a>
                @elseif($quizProgress->isCompleted)
                    <a href="/deck/{{ $learnProgress->quizProgress->id }}/quiz" class="learn-btn learn-btn-primary">Review
                        Quiz</a>
                @endif
            </div>
        </section>
    @endif

    <main class="learn-deck-view-grid">
        <div class="learn-deck-view-card" id="card-container">
            <div class="learn-deck-view-description">
                <p>{{ $currentLearnCard->content }}</p>
            </div>
        </div>
    </main>

    <footer class="learn-deck-navigation">
        <button class="learn-btn learn-btn-secondary" wire:click="previousLearnCard"
            {{ $currentIndex == 0 ? 'disabled' : '' }}>
            Previous
        </button>
        <button class="learn-btn learn-btn-secondary" wire:click="nextLearnCard"
            {{ $currentIndex >= $learnProgress->deck->cards->count() - 1 ? 'disabled' : '' }}>
            Next
        </button>
    </footer>
</div>
