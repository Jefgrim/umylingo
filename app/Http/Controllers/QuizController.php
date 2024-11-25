<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\DeckProgress;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        //    
    }
    public function create($deck_id)
    {
        // 
    }
    public function show(Deck $deck)
    {
        // if ($quiz->answer == $quiz->card->choices[0]->choice) {
        //     $result = true;
        // } else {
        //     $result = false;
        // }
        $cards = $deck->cards;

        // dd($cards[0]->quizzes);

        foreach ($cards as $card) {
            Quiz::firstOrCreate([
                'card_id' => $card->id,
                'user_id' => Auth::user()->id,
            ]);
        }

        DeckProgress::firstOrCreate([
            'deck_id' => $deck->id,
            'user_id' => Auth::user()->id,
            'isQuizStarted' => 1
        ]);

        return view('quiz.show', ['deck' => $deck]);
    }
    public function store()
    {
        // 
    }
    public function edit(Quiz $quiz)
    {
        // 
    }
    public function update(Quiz $quiz)
    {
        // 
    }
    public function destroy(Quiz $quiz)
    {
        // 
    }
}
