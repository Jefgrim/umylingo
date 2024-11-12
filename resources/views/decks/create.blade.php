<x-app_layout>
    <div class="main">
        <div>
            <h2>Create Deck</h2>
        </div>
        <div class="cards-container">
            <div class="card">
                {{-- @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <span>{{ $error }}</span>
                    @endforeach
                @endif --}}
                <form action="/app/decks/create" method="POST">
                    @csrf
                    @error('language')
                        <span>{{ $message }}</span>
                    @enderror
                    <input type="text" name="language" id="" placeholder="Language" required>
                    @error('deck_description')
                        <span>{{ $message }}</span>
                    @enderror
                    <textarea name="deck_description" id="" placeholder="Deck Description" required></textarea>

                    <h2>Achievement</h2>

                    <input type="text" name="achievement_title" placeholder="Achievement Title" required>
                    <textarea name="achievement_description" placeholder="Achievement Description" required></textarea>
                    <button>Add</button>
                </form>
            </div>
        </div>
    </div>
</x-app_layout>
