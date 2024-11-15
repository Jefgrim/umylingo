<x-app_layout>
    <div class="deck-info">
        <h2>{{ ucfirst($deck->language) }}</h2>
        <h2>{{ $deck->deck_description }}</h2>
    </div>
    @can('administrate')
        <a href="/app/decks/{{ $deck->id }}/edit">Edit Deck</a>
        <a href="/app/cards/{{ $deck->id }}/create">Add Card</a>
    @endcan
    <div class="cards-container deck-container">
        @foreach ($deck->cards as $card)
            <div class="card deck-card">
                <div class="deck-card-content">
                    <span class="deck-card-content-title card-span">{{ ucfirst($card->content) }}</span>

                </div>
                <div class="deck-card-footer">
                    @can('administrate')
                        <a href="/app/cards/{{ $card->id }}/edit" class="deck-footer-buttons">Edit</a>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
    @if ($deck->cards->count() > 0)
        @if ($deck->cards->count() > 0)
            @if (Auth::user()->progress->where('deck_id', $deck->id)->first() === null)
                <a href="/app/quiz/{{ $deck->id }}">Start Quiz</a>
            @else
                @if (Auth::user()->progress->where('deck_id', $deck->id)->first()->isQuizStarted)
                    <a href="/app/quiz/{{ $deck->id }}">Continue Quiz</a>
                @endif
            @endif
        @endif
    @endif

</x-app_layout>
