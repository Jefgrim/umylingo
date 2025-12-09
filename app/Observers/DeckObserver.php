<?php

namespace App\Observers;

use App\Models\Deck;
use Illuminate\Support\Facades\Log;

class DeckObserver
{
    public function created(Deck $deck): void
    {
        Log::info('Deck created', [
            'deck_id' => $deck->id,
            'user_id' => auth()->id(),
            'user' => auth()->user()?->username,
            'title' => $deck->title,
        ]);
    }

    public function updated(Deck $deck): void
    {
        $changes = $deck->getChanges();
        Log::info('Deck updated', [
            'deck_id' => $deck->id,
            'user_id' => auth()->id(),
            'user' => auth()->user()?->username,
            'changes' => $changes,
        ]);
    }

    public function deleted(Deck $deck): void
    {
        Log::warning('Deck deleted', [
            'deck_id' => $deck->id,
            'user_id' => auth()->id(),
            'user' => auth()->user()?->username,
            'title' => $deck->title,
        ]);
    }
}
