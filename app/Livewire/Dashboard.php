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
    
    // User Engagement
    public $quizCompletionRate;
    public $avgSessionDuration;
    public $learningStreakAvg;
    public $cardsMastered;
    
    // Learning Performance
    public $avgQuizScore;
    public $timeToDeckCompletion;
    public $mostChallengingCards = [];
    public $learningVelocity;
    
    // Content Insights
    public $mostPopularDecks = [];
    public $deckCompletionRate;
    public $cardErrorRate;
    public $contentEngagement;
    
    // User Health
    public $churnRate;
    public $newUserQuality;
    public $timeToFirstQuiz;
    public $featureAdoption = [];
    
    // Growth
    public $weekOverWeekGrowth;
    public $userSegmentation = [];
    public $cohortComparison = [];

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
        
        // === User Engagement ===
        $this->quizCompletionRate = $this->getQuizCompletionRate();
        $this->avgSessionDuration = $this->getAvgSessionDuration();
        $this->learningStreakAvg = $this->getLearningStreakAvg();
        $this->cardsMastered = $this->getCardsMastered();
        
        // === Learning Performance ===
        $this->avgQuizScore = $this->getAvgQuizScore();
        $this->timeToDeckCompletion = $this->getTimeToDeckCompletion();
        $this->mostChallengingCards = $this->getMostChallengingCards();
        $this->learningVelocity = $this->getLearningVelocity();
        
        // === Content Insights ===
        $this->mostPopularDecks = $this->getMostPopularDecks();
        $this->deckCompletionRate = $this->getDeckCompletionRate();
        $this->cardErrorRate = $this->getCardErrorRate();
        $this->contentEngagement = $this->getContentEngagement();
        
        // === User Health ===
        $this->churnRate = $this->getChurnRate();
        $this->newUserQuality = $this->getNewUserQuality();
        $this->timeToFirstQuiz = $this->getTimeToFirstQuiz();
        $this->featureAdoption = $this->getFeatureAdoption();
        
        // === Growth ===
        $this->weekOverWeekGrowth = $this->getWeekOverWeekGrowth();
        $this->userSegmentation = $this->getUserSegmentation();
        $this->cohortComparison = $this->getCohortComparison();
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
    
    // === User Engagement Metrics ===
    
    private function getQuizCompletionRate(): float
    {
        $started = QuizProgress::where('isStarted', true)->count();
        if ($started == 0) return 0;
        $completed = QuizProgress::where('isCompleted', true)->count();
        return round($completed / $started * 100, 1);
    }
    
    private function getAvgSessionDuration(): float
    {
        $sessions = QuizProgress::select(DB::raw('TIMESTAMPDIFF(MINUTE, created_at, updated_at) as duration'))
            ->where('created_at', '!=', DB::raw('updated_at'))
            ->get();
        if ($sessions->count() == 0) return 0;
        return round($sessions->avg('duration'), 1);
    }
    
    private function getLearningStreakAvg(): float
    {
        $users = User::where('isAdmin', false)->get();
        if ($users->count() == 0) return 0;
        
        $totalStreak = 0;
        foreach ($users as $user) {
            $dates = Quiz::where('user_id', $user->id)
                ->pluck('created_at')
                ->map(fn($d) => $d->toDateString())
                ->unique()
                ->sort()
                ->values();
            
            if ($dates->isEmpty()) continue;
            
            $maxStreak = 1;
            $currentStreak = 1;
            for ($i = 1; $i < $dates->count(); $i++) {
                $prev = strtotime($dates[$i-1]);
                $curr = strtotime($dates[$i]);
                if ($curr - $prev == 86400) { // 1 day
                    $currentStreak++;
                    $maxStreak = max($maxStreak, $currentStreak);
                } else {
                    $currentStreak = 1;
                }
            }
            $totalStreak += $maxStreak;
        }
        
        return round($totalStreak / $users->count(), 1);
    }
    
    private function getCardsMastered(): int
    {
        // Cards with 80%+ accuracy
        $cards = DB::table('quizzes')
            ->join('cards', 'quizzes.card_id', '=', 'cards.id')
            ->select('cards.id', DB::raw('AVG(CASE WHEN quizzes.isCorrect = 1 THEN 1.0 ELSE 0.0 END) as accuracy'), DB::raw('COUNT(*) as attempts'))
            ->groupBy('cards.id')
            ->having('accuracy', '>=', 0.8)
            ->having('attempts', '>=', 5)
            ->count();
        return $cards;
    }
    
    // === Learning Performance Metrics ===
    
    private function getAvgQuizScore(): float
    {
        $total = Quiz::count();
        if ($total == 0) return 0;
        $correct = Quiz::where('isCorrect', true)->count();
        return round($correct / $total * 100, 1);
    }
    
    private function getTimeToDeckCompletion(): float
    {
        $times = DB::table('decks')
            ->leftJoin('cards', 'decks.id', '=', 'cards.deck_id')
            ->leftJoin('quizzes', 'cards.id', '=', 'quizzes.card_id')
            ->select('decks.id', DB::raw('MAX(quizzes.created_at) as last_activity'), DB::raw('MIN(quizzes.created_at) as first_activity'))
            ->groupBy('decks.id')
            ->get();
        
        if ($times->count() == 0) return 0;
        
        $totalMinutes = 0;
        $count = 0;
        foreach ($times as $t) {
            if ($t->first_activity && $t->last_activity) {
                $first = strtotime($t->first_activity);
                $last = strtotime($t->last_activity);
                $minutes = ($last - $first) / 60;
                $totalMinutes += $minutes;
                $count++;
            }
        }
        
        return $count > 0 ? round($totalMinutes / $count, 1) : 0;
    }
    
    private function getMostChallengingCards(): array
    {
        return DB::table('quizzes')
            ->join('cards', 'quizzes.card_id', '=', 'cards.id')
            ->select('cards.id', 'cards.question', 'cards.content', DB::raw('AVG(CASE WHEN quizzes.isCorrect = 1 THEN 1.0 ELSE 0.0 END) as accuracy'), DB::raw('COUNT(*) as attempts'))
            ->groupBy('cards.id', 'cards.question', 'cards.content')
            ->having('attempts', '>=', 3)
            ->orderBy('accuracy', 'asc')
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'word' => $c->question . ' → ' . $c->content,
                'accuracy' => round($c->accuracy * 100, 1),
            ])
            ->toArray();
    }
    
    private function getLearningVelocity(): float
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $cardsThisWeek = Quiz::whereBetween('created_at', [$startOfWeek, $endOfWeek])->distinct('card_id')->count('card_id');
        $users = User::where('isAdmin', false)->count();
        return $users > 0 ? round($cardsThisWeek / $users, 1) : 0;
    }
    
    // === Content Insights ===
    
    private function getMostPopularDecks(): array
    {
        return DB::table('decks')
            ->leftJoin('cards', 'decks.id', '=', 'cards.deck_id')
            ->leftJoin('quizzes', 'cards.id', '=', 'quizzes.card_id')
            ->select('decks.id', 'decks.language', DB::raw('COUNT(DISTINCT quizzes.user_id) as users'))
            ->groupBy('decks.id', 'decks.language')
            ->orderBy('users', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }
    
    private function getDeckCompletionRate(): float
    {
        $decks = Deck::count();
        if ($decks == 0) return 0;
        
        $completed = DB::table('decks')
            ->leftJoin('cards', 'decks.id', '=', 'cards.deck_id')
            ->leftJoin('quizzes', 'cards.id', '=', 'quizzes.card_id')
            ->select('decks.id', DB::raw('COUNT(DISTINCT cards.id) as total_cards'), DB::raw('COUNT(DISTINCT CASE WHEN quizzes.isCorrect = 1 THEN cards.id END) as correct_cards'))
            ->groupBy('decks.id')
            ->having(DB::raw('correct_cards'), '>=', DB::raw('total_cards * 0.8'))
            ->count();
        
        return round($completed / $decks * 100, 1);
    }
    
    private function getCardErrorRate(): float
    {
        $total = Quiz::count();
        if ($total == 0) return 0;
        $incorrect = Quiz::where('isCorrect', false)->count();
        return round($incorrect / $total * 100, 1);
    }
    
    private function getContentEngagement(): float
    {
        $activeDecks = DB::table('decks')
            ->leftJoin('cards', 'decks.id', '=', 'cards.deck_id')
            ->leftJoin('quizzes', 'cards.id', '=', 'quizzes.card_id')
            ->where('quizzes.created_at', '>=', now()->subDays(30))
            ->distinct('decks.id')
            ->count('decks.id');
        
        $totalDecks = Deck::count();
        return $totalDecks > 0 ? round($activeDecks / $totalDecks * 100, 1) : 0;
    }
    
    // === User Health Metrics ===
    
    private function getChurnRate(): float
    {
        $thirtyDaysAgo = now()->subDays(30);
        $sixtyDaysAgo = now()->subDays(60);
        
        $churned = User::where('isAdmin', false)
            ->whereDoesntHave('quizzes', fn($q) => $q->where('created_at', '>=', $thirtyDaysAgo))
            ->where('created_at', '<', $sixtyDaysAgo)
            ->count();
        
        $total = User::where('isAdmin', false)->where('created_at', '<', $sixtyDaysAgo)->count();
        return $total > 0 ? round($churned / $total * 100, 1) : 0;
    }
    
    private function getNewUserQuality(): float
    {
        $thirtyDaysAgo = now()->subDays(30);
        $newUsers = User::where('isAdmin', false)->where('created_at', '>=', $thirtyDaysAgo)->count();
        
        if ($newUsers == 0) return 0;
        
        $engagedNewUsers = Quiz::whereIn('user_id', User::where('isAdmin', false)->where('created_at', '>=', $thirtyDaysAgo)->pluck('id'))
            ->distinct('user_id')
            ->count('user_id');
        
        return round($engagedNewUsers / $newUsers * 100, 1);
    }
    
    private function getTimeToFirstQuiz(): float
    {
        $users = User::where('isAdmin', false)->get();
        if ($users->count() == 0) return 0;
        
        $totalMinutes = 0;
        $count = 0;
        foreach ($users as $user) {
            $firstQuiz = Quiz::where('user_id', $user->id)->orderBy('created_at')->first();
            if ($firstQuiz) {
                $minutes = $user->created_at->diffInMinutes($firstQuiz->created_at);
                $totalMinutes += $minutes;
                $count++;
            }
        }
        
        return $count > 0 ? round($totalMinutes / $count, 1) : 0;
    }
    
    private function getFeatureAdoption(): array
    {
        $quizUsers = QuizProgress::distinct('user_id')->count('user_id');
        $learnUsers = LearnProgress::distinct('user_id')->count('user_id');
        $totalUsers = User::where('isAdmin', false)->count();
        
        return [
            'quiz_mode' => $totalUsers > 0 ? round($quizUsers / $totalUsers * 100, 1) : 0,
            'learn_mode' => $totalUsers > 0 ? round($learnUsers / $totalUsers * 100, 1) : 0,
        ];
    }
    
    // === Growth Metrics ===
    
    private function getWeekOverWeekGrowth(): float
    {
        $thisWeekStart = now()->startOfWeek();
        $thisWeekEnd = now()->endOfWeek();
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();
        
        $thisWeek = User::where('isAdmin', false)->whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->count();
        $lastWeek = User::where('isAdmin', false)->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        
        if ($lastWeek == 0) return 0;
        return round(($thisWeek - $lastWeek) / $lastWeek * 100, 1);
    }
    
    private function getUserSegmentation(): array
    {
        $allUsers = User::where('isAdmin', false)->count();
        
        $active = $this->activeUsersCount(30);
        $inactive = $allUsers - $active;
        
        return [
            'active' => ['count' => $active, 'percent' => $allUsers > 0 ? round($active / $allUsers * 100, 1) : 0],
            'inactive' => ['count' => $inactive, 'percent' => $allUsers > 0 ? round($inactive / $allUsers * 100, 1) : 0],
        ];
    }
    
    private function getCohortComparison(): array
    {
        $cohorts = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->startOfMonth()->subMonths($i);
            $end = (clone $start)->endOfMonth();
            
            $cohort = User::where('isAdmin', false)->whereBetween('created_at', [$start, $end])->count();
            $cohorts[] = [
                'label' => $start->format('Y-m'),
                'users' => $cohort,
            ];
        }
        return $cohorts;
    }
}
