<?php

namespace App\Providers;

use App\Models\DeckProgress;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('pagination::simple-default');

        Gate::define('administrate', function () {
            return Auth::user()->isAdmin;
        });

        Gate::define('access-deck-progress', function ($user, DeckProgress $deckProgress) {
            return $deckProgress->user_id === $user->id;
        });
    }
}
