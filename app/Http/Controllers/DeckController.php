<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DeckController extends Controller
{
    public function index()
    {
        $decks = Deck::with('cards')->latest()->paginate(6);
        return view('decks.index', [
            'decks' => $decks
        ]);
    }
    public function create()
    {
        return view('decks.create');
    }
    public function show(Deck $deck)
    {
        // $userId = Auth::user()->id; // Get the ID of the currently logged-in user
        // // Filter quizzes for the current user
        // $quizzes = $deck->cards[0]->quizzes->where('user_id', $userId);
        // dd($quizzes);
        
        return view('decks.show', ['deck' => $deck]);
    }
    public function store()
    {
        request()->validate([
            'language' => ['required'],
            'deck_description' => ['required']
        ]);

        Deck::create([
            'language' => request('language'),
            'deck_description' => request('deck_description')
        ]);

        return redirect('/app/decks');
    }
    public function edit(Deck $deck)
    {
        return view('decks.edit', ['deck' => $deck]);
    }
    public function update(Deck $deck)
    {
        // Validate
        request()->validate([
            'language' => ['required'],
            'deck_description' => ['required']
        ]);
        // Authorize

        // Update the Deck

        $deck->update([
            'language' => request('language'),
            'deck_description' => request('deck_description'),
        ]);

        // Redirect to the deck page
        return redirect(to: '/app/decks/' . $deck->id);
    }
    public function destroy(Deck $deck)
    {
        // Authorize

        // Delete the Deck
        $deck->delete();

        // Redirect

        return redirect('/app/decks');
    }
}
