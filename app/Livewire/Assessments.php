<?php

namespace App\Livewire;

use App\Models\LearnProgress;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Assessments extends Component
{
    public $accuracy;
    public $avgResponseTime;
    public $deckProgress = [];

    public function mount()
    {
        $userId = Auth::user()->id;

        $this->accuracy = $this->calculateAccuracy($userId);
        $this->avgResponseTime = $this->calculateResponseTime($userId);
        $this->deckProgress = $this->fetchDeckProgress($userId);
    }

    public function calculateAccuracy($userId)
    {
        return Quiz::where('user_id', $userId)->avg('is_correct') * 100;
    }

    public function calculateResponseTime($userId)
    {
        return Quiz::where('user_id', $userId)
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, updated_at)) as avg_time')
            ->value('avg_time');
    }

    public function fetchDeckProgress($userId)
    {
        $progressData = LearnProgress::where('user_id', $userId)
            ->join('decks', 'learn_progress.deck_id', '=', 'decks.id')
            ->select('decks.name', 'learn_progress.current_index', 'decks.total_cards')
            ->get();

        return $progressData->map(function ($progress) {
            return [
                'deck' => $progress->name,
                'progress' => ($progress->current_index / $progress->total_cards) * 100,
            ];
        });
    }
    public function render()
    {
        return view('livewire.assessments');
    }
}
