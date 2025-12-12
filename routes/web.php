<?php


use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TwoFactorChallengeController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Admin\LogController as AdminLogController;
use App\Livewire\Achievements;
use App\Livewire\AdminProfile;
use App\Livewire\Assessment;
use App\Livewire\Assessments;
use App\Livewire\CreateAchievements;
use App\Livewire\CreateDeck;
use App\Livewire\Dashboard;
use App\Livewire\DashboardAchievements;
use App\Livewire\DashboardDecks;
use App\Livewire\Decks;
use App\Livewire\EditDeck;
use App\Livewire\LearnDeck;
use App\Livewire\Notes;
use App\Livewire\Profile;
use App\Livewire\QuizDeck;
use App\Livewire\SecurityQuestions;
use App\Models\Deck;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    $deck = Deck::find(10);
    dd($deck->achievement);
})
    ->middleware('auth')
    ->can('administrate');;


Route::view('/', 'home');

Route::get('/profile', Profile::class)
    ->middleware('auth')
    ->can('learn');

Route::get('/security-questions', SecurityQuestions::class)
    ->middleware('auth')
    ->can('learn');

Route::get('/decks', Decks::class)
    ->middleware('auth')
    ->can('learn');

Route::get('/achievements', Achievements::class)
    ->middleware('auth')
    ->can('learn');

Route::get('/dashboard', Dashboard::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/dashboard/profile', AdminProfile::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/dashboard/decks', DashboardDecks::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/dashboard/logs', [AdminLogController::class, 'index'])
    ->middleware('auth')
    ->can('administrate')
    ->name('admin.logs');

Route::post('/dashboard/logs/verify', [AdminLogController::class, 'verify'])
    ->middleware('auth')
    ->can('administrate')
    ->name('admin.logs.verify');

Route::get('/deck/{deck}/edit', EditDeck::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/deck/create', CreateDeck::class)
    ->middleware('auth')
    ->can('administrate');

Route::get('/deck/{learnProgress}/learn', LearnDeck::class)
    ->middleware('auth')
    ->can('learn');

Route::get('/deck/{quizProgress}/quiz', QuizDeck::class)
    ->middleware('auth')
    ->can('learn');

// Auth
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy']);

// Password Reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::get('/two-factor', [TwoFactorController::class, 'edit'])->name('two-factor.index');
    Route::post('/two-factor/confirm', [TwoFactorController::class, 'store'])->name('two-factor.confirm');
    Route::post('/two-factor/recovery-codes', [TwoFactorController::class, 'regenerate'])->name('two-factor.recovery-codes');
    Route::delete('/two-factor', [TwoFactorController::class, 'destroy'])->name('two-factor.disable');
});

Route::get('/two-factor-challenge', [TwoFactorChallengeController::class, 'create'])->name('two-factor.challenge');
Route::post('/two-factor-challenge', [TwoFactorChallengeController::class, 'store']);
