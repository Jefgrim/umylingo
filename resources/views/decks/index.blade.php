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
                    <a href="/app/quiz/{{ $deck->id }}" class="deck-footer-buttons">Quiz</a>
                    @can('administrate')
                        <a href="/app/decks/{{ $deck->id }}/edit" class="deck-footer-buttons">Edit</a>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
    {{ $decks->links() }}
</x-app_layout>
