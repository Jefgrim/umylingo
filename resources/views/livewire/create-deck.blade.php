<div>
    <div class="form-container-edit-create-deck">
        <h1>Create Deck</h1>
        <form wire:submit.prevent='store'>
            <div>
                <h3>Deck Information</h3>
                <div class="form-group">
                    <label for="language">Language</label>
                    <input id="language" type="text" placeholder="Language" required wire:model='form.language'>
                    <span class="error-message">
                        @error('form.language') {{ $message }} @enderror
                    </span>
                </div>
                <div class="form-group">
                    <label for="deck_description">Deck Description</label>
                    <textarea id="deck_description" name="deck_description" placeholder="Deck Description" required wire:model='form.deck_description'></textarea>
                    <span class="error-message">
                        @error('form.deck_description') {{ $message }} @enderror
                    </span>
                </div>
            </div>

            <div>
                <h3>Cards Details</h3>
                <div>
                    <label for="cardCount" class="form-group-label">Total Cards: {{ $cardCount }}</label>
                    <input id="cardCount" type="number" wire:model.live='cardCount' min="1" placeholder="Number of Cards">
                </div>

                @for ($i = 0; $i < $cardCount; $i++)
                    <div class="card-container">
                        <h4>Card {{ $i + 1 }}</h4>
                        <div class="form-group">
                            <label for="content-{{ $i }}">Card Content</label>
                            <textarea id="content-{{ $i }}" required wire:model.live="cards.{{ $i }}.content" placeholder="Card Content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="question-{{ $i }}">Card Question</label>
                            <textarea id="question-{{ $i }}" required wire:model.live="cards.{{ $i }}.question" placeholder="Card Question"></textarea>
                        </div>

                        <div class="choices-container">
                            <label>Choices:</label>
                            @foreach ($cards[$i]['choices'] as $choiceIndex => $choice)
                                <div class="choice">
                                    <input type="text"
                                        wire:model.live="cards.{{ $i }}.choices.{{ $choiceIndex }}.choice"
                                        placeholder="Choice {{ $choiceIndex + 1 }}"
                                        required>
                                    <input type="radio" name="correctChoice-{{ $i }}"
                                        wire:click="setCorrectChoice({{ $i }}, {{ $choiceIndex }})"
                                        required>
                                    <label>Correct</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>

            <div class="submit-container">
                <button type="submit">Create Deck</button>
                <button type="button" wire:click='cancel'>Cancel</button>
            </div>
        </form>
    </div>
</div>
