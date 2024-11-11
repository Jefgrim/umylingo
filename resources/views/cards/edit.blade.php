<x-app_layout>
    <div class="main">
        <div>
            <h2>Edit Card</h2>
        </div>
        <div class="cards-container">
            <div class="card">
                <form action="/app/cards/{{ $card->id }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @error('content')
                        <span>{{ $message }}</span>
                    @enderror

                    <textarea name="content" id="" placeholder="Content" required>{{ $card->content }}</textarea>
                    <textarea name="question" id="" placeholder="Question" required>{{ $card->question }}</textarea>
                    <input type="text" name="answer" id="" placeholder="Answer" required
                        value="{{ $card->answer }}">
                    <a href="/app/decks/{{ $card->deck_id }}">Cancel</a>

                    <input type="text" name="choice1" value="{{$card->choices[0]->choice}}">
                    <input type="text" name="choice2" value="{{$card->choices[1]->choice}}">
                    <input type="text" name="choice3" value="{{$card->choices[2]->choice}}">
                    <input type="text" name="choice4" value="{{$card->choices[3]->choice}}">
                    <button>Update</button>
                </form>
                <form action="/app/cards/{{ $card->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Archive</button>
                </form>
            </div>
        </div>
    </div>
</x-app_layout>
