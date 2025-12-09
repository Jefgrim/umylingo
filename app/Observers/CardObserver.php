<?php

namespace App\Observers;

use App\Models\Card;
use Illuminate\Support\Facades\Log;

class CardObserver
{
    public function created(Card $card): void
    {
        Log::info('Card created', [
            'card_id' => $card->id,
            'deck_id' => $card->deck_id,
            'user_id' => auth()->id(),
            'user' => auth()->user()?->username,
        ]);
    }

    public function updated(Card $card): void
    {
        $changes = $card->getChanges();
        Log::info('Card updated', [
            'card_id' => $card->id,
            'deck_id' => $card->deck_id,
            'user_id' => auth()->id(),
            'user' => auth()->user()?->username,
            'changes' => $changes,
        ]);
    }

    public function deleted(Card $card): void
    {
        Log::warning('Card deleted', [
            'card_id' => $card->id,
            'deck_id' => $card->deck_id,
            'user_id' => auth()->id(),
            'user' => auth()->user()?->username,
        ]);
    }
}
