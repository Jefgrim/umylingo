<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Choice;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        //    
    }
    public function create($deck_id)
    {
        return view('cards.create', ['deck_id' => $deck_id]);
    }
    public function show(Card $card)
    {
        //    
    }
    public function store()
    {
        request()->validate([
            'content' => ['required'],
            'question' => ['required'],
            'answer' => ['required'],
            'deck_id' => ['required'],
            'choice1' => ['required', 'same:answer'],
            'choice2' => ['required'],
            'choice3' => ['required'],
            'choice4' => ['required'],
        ], [
            'choice1.same' => 'Choice 1 must be the same with the answer.'
        ]);

        $card = Card::create([
            'content' => request('content'),
            'question' => request('question'),
            'answer' => request('answer'),
            'deck_id' => request('deck_id')
        ]);

        Choice::create([
            'card_id' => $card->id,
            'choice' => request("choice1"),
            'isCorrect' => 1
        ]);

        Choice::create([
            'card_id' => $card->id,
            'choice' => request("choice2")
        ]);

        Choice::create([
            'card_id' => $card->id,
            'choice' => request("choice3")
        ]);

        Choice::create([
            'card_id' => $card->id,
            'choice' => request("choice4")
        ]);

        return redirect('/app/decks/' . $card->deck_id);
    }
    public function edit(Card $card)
    {
        return view('cards.edit', ['card' => $card]);
    }
    public function update(Card $card)
    {
        // Validate
        request()->validate([
            'question' => ['required'],
            'content' => ['required'],
            'answer' => ['required'],
            'choice1' => ['required', 'same:answer'],
            'choice2' => ['required'],
            'choice3' => ['required'],
            'choice4' => ['required'],
        ]);
        // Authorize

        // Update the Card

        $card->update([
            'question' => request('question'),
            'content' => request('content'),
            'answer' => request('answer'),
        ]);

        // Update the choices
        $card->choices[0]->update([
            'choice' => request('choice1')
        ]);

        $card->choices[1]->update([
            'choice' => request('choice2')
        ]);

        $card->choices[2]->update([
            'choice' => request('choice3')
        ]);

        $card->choices[3]->update([
            'choice' => request('choice4')
        ]);

        // Redirect to the deck page
        return redirect(to: '/app/decks/' . $card->deck_id);
    }
    public function destroy(Card $card)
    {
        // Authorize

        // Delete the Deck
        $card->delete();

        // Redirect

        return redirect('/app/decks/' . $card->deck_id);
    }
}
