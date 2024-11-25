<?php


use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Livewire\CreateDeck;
use App\Livewire\Dashboard;
use App\Livewire\DashboardAchievements;
use App\Livewire\DashboardDecks;
use App\Livewire\Decks;
use App\Livewire\EditDeck;
use App\Livewire\LearnDeck;
use App\Livewire\QuizDeck;
use App\Models\Deck;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    $deck = Deck::find(10);
    dd($deck->achievement);
})
    ->middleware('auth')
    ->can('administrate');;


Route::view('/', 'home');

Route::get('/dashboard', Dashboard::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/dashboard/decks', DashboardDecks::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/deck/{deck}/edit', EditDeck::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/dashboard/achievements', DashboardAchievements::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/decks', Decks::class)
    ->middleware('auth');

Route::get('/deck/create', CreateDeck::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/deck/{learnProgress}/learn', LearnDeck::class)
    ->middleware('auth');

    Route::get('/deck/{quizProgress}/quiz', QuizDeck::class)
    ->middleware('auth');


// Auth
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);
