<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

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
    public function show(Quiz $quiz)
    {
        // if ($quiz->answer == $quiz->card->choices[0]->choice) {
        //     $result = true;
        // } else {
        //     $result = false;
        // }
        dd($quiz->user);
        // return view('quiz.show', ['deck' => $quiz]);
    }
    public function store()
    {
        request()->validate([
            'content' => ['required'],
            'question' => ['required'],
            'answer' => ['required'],
            'deck_id' => ['required']
        ]);

        Quiz::create([
            'content' => request('content'),
            'question' => request('question'),
            'answer' => request('answer'),
            'deck_id' => request('deck_id')
        ]);

        return redirect('/app/quiz');
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
