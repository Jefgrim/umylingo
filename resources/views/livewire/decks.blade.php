<div>
    <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
        <h2>Available Decks</h2>
        @can('administrate')
            <a class="decks-button" href="/app/decks/create">Add Deck</a>
        @endcan
    </div>
    <div class="cards-container decks-container">
        @foreach ($decks as $deck)
            <div class="card decks" wire:key='{{ $deck->id }}'>
                <div class="deck-header">
                    <span>{{ ucfirst($deck->language) }}</span>
                </div>
                <div class="deck-content">
                    <span class="deck-content-title">{{ $deck->deck_description }}</span>
                    <span class="deck-content-subtitle">{{ $deck->cards->count() }} Cards</span>
                </div>
                <div class="deck-footer">
                    <a href="/deck/{{ $deck->id }}" class="deck-footer-buttons">Learn</a>
                    @can('administrate')
                        <a href="/deck/{{ $deck->id }}/edit" class="deck-footer-buttons">Edit</a>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
</div>