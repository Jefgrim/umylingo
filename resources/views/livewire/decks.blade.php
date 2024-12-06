<div class="deck-view-page">
    <div class="deck-view-header">
        <h2>Available Decks</h2>
        {{ 'test' }}
    </div>
    <div class="deck-view-grid">
        @foreach ($learnProgresses as $learnProgress)
            <div class="deck-view-card" wire:key="{{ $learnProgress->deck->id }}">
                <div class="deck-view-language">
                    {{ ucfirst($learnProgress->deck->language) }}
                    {{ '(' }}{{ $learnProgress->deck->cards->count() }} Cards{{ ')' }}
                </div>
                <div class="deck-view-description">
                    <p>{{ $learnProgress->deck->deck_description }}</p>
                </div>
                <div class="deck-view-actions">
                    @if ($learnProgress->isStarted && $learnProgress->isCompleted)
                        <a href="/deck/{{ $learnProgress->id }}/learn" class="btn btn-primary">Review</a>
                    @elseif($learnProgress->isStarted && !$learnProgress->isCompleted)
                        <a href="/deck/{{ $learnProgress->id }}/learn" class="btn btn-primary">Continue Learning</a>
                    @elseif(!$learnProgress->isStarted && !$learnProgress->isCompleted)
                        <a href="/deck/{{ $learnProgress->id }}/learn" class="btn btn-primary">Learn</a>
                    @endif

                    @if ($learnProgress->isCompleted)
                        @if (!$learnProgress->quizProgress->isCompleted && !$learnProgress->quizProgress->isStarted)
                            <a href="/deck/{{ $learnProgress->quizProgress->id }}/quiz" class="btn btn-primary">Start
                                Quiz</a>
                        @elseif($learnProgress->quizProgress->isStarted && !$learnProgress->quizProgress->isCompleted)
                            <a href="/deck/{{ $learnProgress->quizProgress->id }}/quiz"
                                class="btn btn-primary">Continue
                                Quiz</a>
                        @elseif($learnProgress->quizProgress->isStarted && $learnProgress->quizProgress->isCompleted)
                            <a href="/deck/{{ $learnProgress->quizProgress->id }}/quiz" class="btn btn-primary">Review
                                Quiz</a>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
