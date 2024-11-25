<div class="deck-view-page">
    <div class="deck-view-header">
        <h2>Available Decks</h2>
        <a href="/deck/create" class="btn btn-primary add-deck-button">Add Deck</a>
    </div>
    <div class="deck-view-grid">
        @foreach ($decks as $deck)
            <div class="deck-view-card" wire:key="{{ $deck->id }}">
                <div class="deck-view-language">
                    {{ ucfirst($deck->language) }}
                </div>
                <div class="deck-view-description">
                    <p>{{ str($deck->deck_description)->words(10) }}</p>
                    <p>{{ $deck->cards->count() }} Cards</p>
                </div>
                <div class="deck-view-actions">
                    <a href="/deck/{{ $deck->id }}/edit" class="btn btn-secondary">Edit</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
