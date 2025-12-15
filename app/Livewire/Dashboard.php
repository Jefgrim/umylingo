<?php

namespace App\Livewire;

use App\Models\Card;
use App\Models\Deck;
use App\Models\QuizProgress;
use App\Models\User;
use App\Models\LearnProgress;
use App\Models\Quiz;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Dashboard')]
class Dashboard extends AdminComponent
{
    public $currentUsers;
    public $currentUsersPerMonth;
    public $totalDecks;
    public $totalCards;
    public $totalQuizzesStarted;
    public $totalQuizzesCompleted;
    public $dau;
    public $wau;
    public $mau;
    public $funnel = [];
    public $deckTop = [];
    public $deckBottom = [];
    public $retention = [];

    public function mount()
    {
        // Create an array of months (1 to 12)
        $months = range(1, 12);
        $this->currentUsers = User::where('isAdmin', false)->count();

        $userCounts = User::where('isAdmin', false)
    ->whereYear('created_at', now()->year)
    ->get()
    ->groupBy(fn($user) => $user->created_at->format('n')) // Month as number 1-12
    ->map(fn($group) => $group->count())
    ->toArray();


        // Initialize the currentUsers array with 0 for each month
        $this->currentUsersPerMonth = array_fill_keys($months, 0);

        // Update the currentUsers array with the actual user counts
        foreach ($userCounts as $month => $count) {
            $this->currentUsersPerMonth[$month] = $count;
        }

        // dd($this->currentUsersPerMonth);
        $this->totalDecks = Deck::count();
        $this->totalCards = Card::count();
        $this->totalQuizzesStarted = QuizProgress::where('isStarted', true)->count();
        $this->totalQuizzesCompleted = QuizProgress::where('isCompleted', true)->count();

        // Activity metrics (unique users active over windows)
        $this->dau = $this->activeUsersCount(1);
        $this->wau = $this->activeUsersCount(7);
        $this->mau = $this->activeUsersCount(30);

        // Activation funnel (all-time)
        $this->funnel = [
            'registered' => User::where('isAdmin', false)->count(),
            'learn_started' => LearnProgress::distinct('user_id')->count('user_id'),
            'quiz_started' => QuizProgress::where('isStarted', true)->distinct('user_id')->count('user_id'),
            'quiz_completed' => QuizProgress::where('isCompleted', true)->distinct('user_id')->count('user_id'),
        ];

        // Deck difficulty from quiz answers (min 10 attempts)
        $perDeck = DB::table('quizzes')
            ->join('cards', 'quizzes.card_id', '=', 'cards.id')
            ->join('decks', 'cards.deck_id', '=', 'decks.id')
            ->select(
                'decks.id as deck_id',
                'decks.language',
                'decks.deck_description',
                DB::raw('AVG(CASE WHEN quizzes.isCorrect = 1 THEN 1.0 ELSE 0.0 END) as accuracy'),
                DB::raw('COUNT(*) as attempts')
            )
            ->groupBy('decks.id', 'decks.language', 'decks.deck_description')
            ->having('attempts', '>=', 10)
            ->get();

        $sorted = collect($perDeck)->sortBy('accuracy');
        $this->deckBottom = $sorted->take(5)->values()->all();
        $this->deckTop = $sorted->reverse()->take(5)->values()->all();

        // Simple monthly retention (last 6 cohorts): percent users with any activity within 30 days of signup
        $this->retention = $this->computeMonthlyRetention(6);
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    private function activeUsersCount(int $days): int
    {
        $key = "active_users_{$days}";
        return Cache::remember($key, now()->addMinutes(10), function () use ($days) {
            $since = now()->subDays($days);
            $ids = collect();
            $ids = $ids->merge(
                Quiz::where('created_at', '>=', $since)->pluck('user_id')
            );
            $ids = $ids->merge(
                QuizProgress::where('updated_at', '>=', $since)->pluck('user_id')
            );
            $ids = $ids->merge(
                LearnProgress::where('updated_at', '>=', $since)->pluck('user_id')
            );
            return $ids->filter()->unique()->count();
        });
    }

    private function computeMonthlyRetention(int $months = 6): array
    {
        $results = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $start = now()->startOfMonth()->subMonths($i);
            $end = (clone $start)->endOfMonth();
            $users = User::where('isAdmin', false)
                ->whereBetween('created_at', [$start, $end])
                ->get(['id', 'created_at']);

            $cohort = $users->count();
            $retained = 0;
            if ($cohort > 0) {
                // For simplicity, consider activity within 30 days of signup
                foreach ($users as $u) {
                    $from = $u->created_at;
                    $to = (clone $from)->addDays(30);
                    $active = Quiz::where('user_id', $u->id)->whereBetween('created_at', [$from, $to])->exists()
                        || QuizProgress::where('user_id', $u->id)->whereBetween('updated_at', [$from, $to])->exists()
                        || LearnProgress::where('user_id', $u->id)->whereBetween('updated_at', [$from, $to])->exists();
                    if ($active) $retained++;
                }
            }
            $results[] = [
                'label' => $start->format('Y-m'),
                'cohort' => $cohort,
                'retained' => $retained,
                'rate' => $cohort ? round($retained / $cohort * 100, 1) : 0.0,
            ];
        }
        return $results;
    }
}
