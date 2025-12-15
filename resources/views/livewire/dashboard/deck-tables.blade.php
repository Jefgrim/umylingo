<div class="tables-container">
    <div class="table-card table-card-green">
        <h3 class="table-title table-title-green">Top Performing Decks</h3>
        @forelse($deckTop as $d)
            <button onclick="openDeckModal('{{ $d->deck_id }}', '{{ $d->language }}', '{{ addslashes($d->deck_description) }}', {{ $d->accuracy }}, {{ $d->attempts }})"
                class="deck-button deck-button-green">
                <div class="deck-button-header">
                    <span class="deck-button-title">{{ $d->language }} — {{ \Illuminate\Support\Str::limit($d->deck_description, 35) }}</span>
                    <span class="deck-pill deck-pill-green">{{ number_format($d->accuracy * 100, 1) }}%</span>
                </div>
                <div class="deck-progress deck-progress-green">
                    <div style="width: {{ min($d->accuracy * 100, 100) }}%;"></div>
                </div>
                <small class="deck-subtext">{{ $d->attempts }} answers</small>
            </button>
        @empty
            <p class="empty-state">No data yet</p>
        @endforelse
    </div>

    <div class="table-card table-card-red">
        <h3 class="table-title table-title-red">Decks Needing Attention</h3>
        @forelse($deckBottom as $d)
            <button onclick="openDeckModal('{{ $d->deck_id }}', '{{ $d->language }}', '{{ addslashes($d->deck_description) }}', {{ $d->accuracy }}, {{ $d->attempts }})"
                class="deck-button deck-button-red">
                <div class="deck-button-header">
                    <span class="deck-button-title">{{ $d->language }} — {{ \Illuminate\Support\Str::limit($d->deck_description, 35) }}</span>
                    <span class="deck-pill deck-pill-red">{{ number_format($d->accuracy * 100, 1) }}%</span>
                </div>
                <div class="deck-progress deck-progress-red">
                    <div style="width: {{ min($d->accuracy * 100, 100) }}%;"></div>
                </div>
                <small class="deck-subtext">{{ $d->attempts }} answers</small>
            </button>
        @empty
            <p class="empty-state">No data yet</p>
        @endforelse
    </div>
</div>
