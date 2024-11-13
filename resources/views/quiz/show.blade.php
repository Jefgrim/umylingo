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
        {{-- {{ dd($deck->cards[0]->quizzes->where('card_id', $deck->cards[0]->id)->where('user_id', Auth::user()->id)->first()) }} --}}
        @foreach ($deck->cards as $card)
            @if ($card->quizzes->where('card_id', $card->id)->where('user_id', Auth::user()->id)->first()->isAnswered)
                <h2>Answered</h2>
            @else
                <h2>Not Answered</h2>
            @endif

            @if (
                $card->quizzes->where('card_id', $card->id)->where('user_id', Auth::user()->id)->first()->isAnswered &&
                    $card->quizzes->where('card_id', $card->id)->where('user_id', Auth::user()->id)->first()->isCorrect)
                <h2>Correct</h2>
            @elseif(
                $card->quizzes->where('card_id', $card->id)->where('user_id', Auth::user()->id)->first()->isAnswered &&
                    !$card->quizzes->where('card_id', $card->id)->where('user_id', Auth::user()->id)->first()->isCorrect)
                <h2>Wrong</h2>
            @endif
            <div class="card deck-card">
                <div class="deck-card-content">
                    <span class="deck-card-content-title card-span">{{ ucfirst($card->question) }}</span>
                </div>
                <div class="deck-card-footer">
                    @can('administrate')
                        <a href="/app/cards/{{ $card->id }}/edit" class="deck-footer-buttons">Edit</a>
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
</x-app_layout>
