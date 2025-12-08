<?php

namespace App\Providers;

use App\Models\LearnProgress;
use App\Models\QuizProgress;
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

        Gate::define('learn', function () {
            return !Auth::user()->isAdmin;
        });

        Gate::define('access-learn-progress', function ($user, LearnProgress $learnProgress) {
            return $learnProgress->user_id === $user->id;
        });
        
        Gate::define('access-quiz-progress', function ($user, QuizProgress $quizProgress) {
            return $quizProgress->user_id === $user->id;
        });
    }
}
