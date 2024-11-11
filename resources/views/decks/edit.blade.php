<x-app_layout>
    <div class="main">
        <div>
            <h2>Edit Deck</h2>
        </div>
        <div class="cards-container">
            <div class="card">
                {{-- @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <span>{{ $error }}</span>
                    @endforeach
                @endif --}}
                <form action="/app/decks/{{ $deck->id }}" method="POST">
                    @csrf
                    @method('PATCH')

                    @error('language')
                        <span>{{ $message }}</span>
                    @enderror

                    <input type="text" name="language" id="" placeholder="Language" value="{{ $deck->language }}"
                        required>

                    @error('deck_description')
                        <span>{{ $message }}</span>
                    @enderror

                    <textarea name="deck_description" id="" placeholder="Deck Description" required>{{ $deck->deck_description }}</textarea>
                    <a href="/app/decks/{{ $deck->id }}">Cancel</a>
                    <button>Update</button>
                </form>
                <form action="/app/decks/{{ $deck->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Archive</button>
                </form>
            </div>
        </div>
    </div>
</x-app_layout>
