<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\SessionController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    // $decks = Deck::all();
    $user = Auth::user();
    dd($user->quizzes[0]->isAnswered);
})
    ->middleware('auth')
    ->can('administrate');;


// Route::resource('/app/decks', DeckController::class, [
//     // 'only' => ['edit'],
//     // 'except' => ['edit']
// ])->middleware('auth');

Route::view('/', 'home');


Route::controller(AppController::class)->group(function () {
    Route::get('/app/dashboard', 'index')
        ->middleware('auth')
        ->can('administrate');
});

Route::controller(DeckController::class)->group(function () {
    Route::get('/app/decks', 'index')
        ->middleware('auth');

    Route::get('/app/decks/create', 'create')
        ->middleware('auth')
        ->can('administrate');

    Route::get('/app/decks/{deck}', 'show')
        ->middleware('auth');

    Route::post('/app/decks/create', 'store')
        ->middleware('auth')
        ->can('administrate');

    Route::get('/app/decks/{deck}/edit', 'edit')
        ->middleware('auth')
        ->can('administrate');

    Route::patch('/app/decks/{deck}', 'update')
        ->middleware('auth')
        ->can('administrate');

    Route::delete('/app/decks/{deck}', 'destroy')
        ->middleware('auth')
        ->can('administrate');
});

Route::controller(CardController::class)->group(function () {

    Route::get('/app/cards/{deck}/create', 'create')
        ->middleware('auth')
        ->can('administrate');

    Route::get('/app/card/{card}', 'show')
        ->middleware('auth');

    Route::post('/app/cards/create', 'store')
        ->middleware('auth')
        ->can('administrate');

    Route::get('/app/cards/{card}/edit', 'edit')
        ->middleware('auth')
        ->can('administrate');

    Route::patch('/app/cards/{card}', 'update')
        ->middleware('auth')
        ->can('administrate');

    Route::delete('/app/cards/{card}', 'destroy')
        ->middleware('auth')
        ->can('administrate');
});


Route::controller(QuizController::class)->group(function () {
    Route::get('/app/quiz/{deck}', 'show')
        ->middleware('auth');

    Route::post('/app/quiz/create', 'store')
        ->middleware('auth');
});


// Auth
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);
