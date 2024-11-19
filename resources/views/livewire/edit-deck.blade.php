<div style="width:100%">
    <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
        <h2>Edit Deck</h2>
    </div>
    <div class="cards-container">
        <div class="card create-deck-card">
            <form wire:submit='update'>
                <p>Deck Information</p>
                <div class="create-deck-inputs-container">
                    @error('language')
                        <span>{{ $message }}</span>
                    @enderror
                    <input type="text" placeholder="Language" required wire:model='form.language'>
                    @error('deck_description')
                        <span>{{ $message }}</span>
                    @enderror
                    <textarea name="deck_description" id="" placeholder="Deck Description" required
                        wire:model='form.deck_description'></textarea>
                </div>

                <p>Deck Achievement</p>
                <div class="create-deck-inputs-container">
                    @error('achievement_title')
                        <span>{{ $message }}</span>
                    @enderror
                    <input type="text" placeholder="Achievement Title" required wire:model='form.achievement_title'>
                    @error('achievement_description')
                        <span>{{ $message }}</span>
                    @enderror
                    <textarea placeholder="Achievement Description" required wire:model='form.achievement_description'></textarea>
                </div>
                <button>Save</button>
            </form>
        </div>
    </div>
</div>
