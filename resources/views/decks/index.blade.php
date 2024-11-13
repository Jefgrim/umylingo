<x-app_layout>
    <div>
        <h2>Available Decks</h2>
    </div>
    @can('administrate')
        <div>
            <a href="/app/decks/create">Add Deck</a>
        </div>
    @endcan
    <div class="cards-container decks-container">
        @foreach ($decks as $deck)
            <div class="card decks">
                <div class="deck-header">
                    <span>{{ ucfirst($deck->language) }}</span>
                </div>
                <div class="deck-content">
                    <span class="deck-content-title">{{ $deck->deck_description }}</span>
                    <span class="deck-content-subtitle">{{ $deck->cards->count() }} Cards</span>
                </div>
                <div class="deck-footer">
                    <a href="/app/decks/{{ $deck->id }}" class="deck-footer-buttons">Learn</a>
                    @if ($deck->cards->count() > 0)
                        @if (Auth::user()->progress->where('deck_id', $deck->id)->first() === null)
                            <a href="/app/quiz/{{ $deck->id }}" class="deck-footer-buttons">Start Quiz</a>
                        @else
                            @if (Auth::user()->progress->where('deck_id', $deck->id)->first()->isQuizStarted)
                                <a href="/app/quiz/{{ $deck->id }}" class="deck-footer-buttons">Continue Quiz</a>
                            @endif
                        @endif
                    @endif
                    @can('administrate')
                        <a href="/app/decks/{{ $deck->id }}/edit" class="deck-footer-buttons">Edit</a>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
    {{ $decks->links() }}
</x-app_layout>
