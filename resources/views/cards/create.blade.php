<x-app_layout>
    <div class="main">
        <div>
            <h2>Add Card</h2>
        </div>
        <div class="cards-container">
            <div class="card">
                <form action="/app/cards/create" method="POST">
                    @csrf

                    @error('content')
                        <span>{{ $message }}</span>
                    @enderror
                    <input type="hidden" name="deck_id" value="{{ $deck_id }}">
                    <textarea name="content" id="" placeholder="content" required></textarea>
                    <textarea name="question" id="" placeholder="Question" required></textarea>
                    <input type="text" name="answer" placeholder="Answer">
                    @error('choice1')
                        <span>{{ $message }}</span>
                    @enderror
                    <input type="text" name="choice1" placeholder="Choice 1 (Correct Answer)">
                    <input type="text" name="choice2" placeholder="Choice 2 (Wrong Answer)">
                    <input type="text" name="choice3" placeholder="Choice 3 (Wrong Answer)">
                    <input type="text" name="choice4" placeholder="Choice 4 (Wrong Answer)">

                    <a href="/app/decks/{{ $deck_id }}">Cancel</a>
                    <button>Add Card</button>
                </form>
            </div>
        </div>
    </div>
</x-app_layout>
