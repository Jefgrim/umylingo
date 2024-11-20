<div>
    <div class="form-container-edit-create-deck">
        <h1>Edit Deck</h1>
        <form wire:submit.prevent='update'>
            <div>
                <h3>Deck Information</h3>
                <div class="form-group">
                    <label for="edit-language">Language</label>
                    <input id="edit-language" type="text" placeholder="Language" required wire:model='form.language'>
                    <span class="error-message">
                        @error('language')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="form-group">
                    <label for="edit-deck_description">Deck Description</label>
                    <textarea id="edit-deck_description" placeholder="Deck Description" required wire:model='form.deck_description'></textarea>
                    <span class="error-message">
                        @error('deck_description')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>

            <div>
                <h3>Deck Achievement</h3>
                <div class="form-group">
                    <label for="edit-achievement_title">Achievement Title</label>
                    <input id="edit-achievement_title" type="text" placeholder="Achievement Title" required
                        wire:model='form.achievement_title'>
                    <span class="error-message">
                        @error('achievement_title')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
                <div class="form-group">
                    <label for="edit-achievement_description">Achievement Description</label>
                    <textarea id="edit-achievement_description" placeholder="Achievement Description" required
                        wire:model='form.achievement_description'></textarea>
                    <span class="error-message">
                        @error('achievement_description')
                            {{ $message }}
                        @enderror
                    </span>
                </div>
            </div>

            <div>
                <h3>Cards Details</h3>
                <div class="form-group">
                    <label for="edit-cardCount" class="form-group-label">Total Cards: {{ $cardCount }}</label>
                    <span class="error-message">
                        @error('cards')
                            {{ $message }}
                        @enderror
                    </span>
                    <button type="button" wire:click="addCard"
                        style="background-color: #0c5894; color: white; padding: 8px 12px; border-radius: 6px; cursor: pointer;">Add
                        Card</button>
                </div>

                @foreach ($cards as $i => $card)
                    <div class="card-container">
                        <h4>Card {{ $i + 1 }}</h4>

                        <div class="form-group">
                            <label for="edit-content-{{ $i }}">Card Content</label>
                            <textarea id="edit-content-{{ $i }}" required wire:model.live="cards.{{ $i }}.content"
                                placeholder="Card Content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-question-{{ $i }}">Card Question</label>
                            <textarea id="edit-question-{{ $i }}" required wire:model.live="cards.{{ $i }}.question"
                                placeholder="Card Question"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-answer-{{ $i }}">Card Answer</label>
                            <input id="edit-answer-{{ $i }}" type="text" placeholder="Card Answer"
                                required wire:model.live="cards.{{ $i }}.answer">
                        </div>

                        <div class="choices-container">
                            <label>Choices:</label>
                            @foreach ($cards[$i]['choices'] as $choiceIndex => $choice)
                                <div class="choice">
                                    <input type="text"
                                        wire:model.live="cards.{{ $i }}.choices.{{ $choiceIndex }}.choice"
                                        placeholder="Choice {{ $choiceIndex + 1 }}" required>
                                    <input type="radio" name="correctChoice-{{ $i }}"
                                        wire:click="setCorrectChoice({{ $i }}, {{ $choiceIndex }})"
                                        {{ $choice['isCorrect'] ? 'checked' : '' }} required>
                                    <label>Correct</label>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" wire:click="removeCard({{ $i }})"
                            style="margin-top: 10px; background-color: #ad3324; color: white; padding: 10px; border-radius: 6px; cursor: pointer;"
                            wire:confirm='Are you sure you want to remove this card?'>Remove
                            Card</button>
                    </div>
                @endforeach
            </div>

            <div class="submit-container">
                <button type="submit">Update Deck</button>
                @if ($deck->isArchived != null)
                    <button type="button" wire:click='toggleArchive' style="background-color: #ad3324;">Unarchive
                        Deck</button>
                @else
                    <button type="button" wire:click='toggleArchive'
                        wire:confirm='Are you sure you want to archive this deck?'
                        style="background-color: #ad3324;">Archive
                        Deck</button>
                @endif

            </div>
        </form>
    </div>
</div>