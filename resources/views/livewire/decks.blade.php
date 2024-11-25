<div class="deck-view-page">
    <div class="deck-view-header">
        <h2>Available Decks</h2>
    </div>
    <div class="deck-view-grid">
        @foreach ($deckProgresses as $deckProgress)
            <div class="deck-view-card" wire:key="{{ $deckProgress->deck->id }}">
                <div class="deck-view-language">
                    {{ ucfirst($deckProgress->deck->language) }}
                </div>
                <div class="deck-view-description">
                    <p>{{ str($deckProgress->deck->deck_description)->words(10) }}</p>
                    <p>{{ $deckProgress->deck->cards->count() }} Cards</p>
                </div>
                <div class="deck-view-actions">
                    @if ($deckProgress->isLearningStarted && $deckProgress->isLearningCompleted)
                        <a href="/deck/{{ $deckProgress->id }}" class="btn btn-primary">Review</a>
                    @elseif($deckProgress->isLearningStarted && !$deckProgress->isLearningCompleted)
                        <a href="/deck/{{ $deckProgress->id }}" class="btn btn-primary">Continue Learning</a>
                    @elseif(!$deckProgress->isLearningStarted && !$deckProgress->isLearningCompleted)
                        <a href="/deck/{{ $deckProgress->id }}" class="btn btn-primary">Learn</a>
                    @endif

                    @if ($deckProgress->isLearningCompleted)
                        <a href="" class="btn btn-primary">Start Quiz</a>
                    @elseif($deckProgress->isQuizStarted && !$deckProgress->isQuizCompleted)
                        <a href="" class="btn btn-primary">Continue Quiz</a>
                    @elseif($deckProgress->isQuizStarted && $deckProgress->isQuizCompleted)
                        <a href="" class="btn btn-primary">Review Quiz</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
