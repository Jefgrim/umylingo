<?php

namespace App\Livewire;

use App\Models\Achievement;
use App\Models\Deck;
use App\Models\LearnProgress;
use App\Models\QuizProgress;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class Decks extends Component
{
    #[Title('Decks View')]
    public function render()
    {

        $decks = Deck::all();

        foreach ($decks as $deck) {
            LearnProgress::firstOrCreate([
                'deck_id' => $deck->id,
                'user_id' => Auth::id(),
            ]);
        }

        $learnProgresses = LearnProgress::where('user_id', Auth::id())->get();
        
        $achievements = Achievement::all();
        
        foreach($achievements as $achievement){
            UserAchievement::firstOrCreate(
                [
                    'achievement_id' => $achievement->id,
                    'user_id' => Auth::id()
                ]
            );
        }

        foreach ($learnProgresses as $learnProgress) {
            QuizProgress::firstOrCreate(
                [
                    'deck_id' => $learnProgress->deck_id,
                    'user_id' => Auth::id(),
                    'learn_progress_id' => $learnProgress->id
                ]
            );
        }

        $quizProgresses = QuizProgress::where('user_id', Auth::id())->get();

        // dd($learnProgresses);

        return view('livewire.decks', [
            'quizProgresses' => $quizProgresses,
            'learnProgresses' => $learnProgresses,
        ]);
    }
}
