<div class="quiz-view-page">
    <!-- Header Section -->
    <header class="quiz-view-header">
        <h2 class="quiz-title">Deck: {{ $quizProgress->deck->language }}</h2>
        <p class="quiz-description">{{ $quizProgress->deck->deck_description }}</p>
    </header>

    <div class="loader" wire:loading.delay></div>

    @if (!$quizProgress->isCompleted)
    <!-- Main Content Section -->
    <main class="quiz-view-content" wire:loading.remove.delay>
        <div class="quiz-card-container">
            <form class="quiz-card">
                <!-- Feedback Message -->
                @if ($isUnAnswered)
                <div class="quiz-feedback-message quiz-wrong">
                    Please answer all the questions.
                </div>
                @endif
                @if ($achievementTitle)
                <div class="quiz-feedback-message quiz-correct">
                    Achievement "{{ $achievementTitle }}" Achieved.
                </div>
                @endif
                @if ($currentQuiz->isAnswered)
                <div class="quiz-feedback-message {{ $currentQuiz->isCorrect ? 'quiz-correct' : 'quiz-wrong' }}">
                    {{ $currentQuiz->isCorrect ? 'Correct Answer!' : 'Wrong Answer' }}
                </div>
                @endif

                <!-- Question -->
                <p class="quiz-question">{{ $currentQuiz->card->question }}</p>

                <!-- Choices -->
                <div class="quiz-choices">
                    @foreach ($currentQuiz->card->choices as $choice)
                    <button type="button"
                        class="quiz-choice-button
                                        {{ $currentQuiz->choice_id == $choice->id && !$currentQuiz->isAnswered ? 'quiz-choice-selected' : '' }}
                                        {{ $currentQuiz->isAnswered && $currentQuiz->choice_id == $choice->id ? ($currentQuiz->isCorrect ? 'quiz-choice-correct' : 'quiz-choice-wrong') : '' }}"
                        wire:click="setAnswer({{ $choice->id }}, {{ $currentQuiz->id }})" {{ $currentQuiz->isAnswered ?
                        'disabled' : '' }}>
                        {{ $choice->choice }}
                    </button>
                    @endforeach
                </div>
            </form>
        </div>
    </main>

    <!-- Navigation Section -->
    <footer class="quiz-navigation" wire:loading.remove>
        <button class="quiz-btn quiz-btn-secondary" wire:click="previousQuizCard" {{ $currentIndex==0 ? 'disabled' : ''
            }}>
            Previous
        </button>
        @if ($quizProgress->quizzes->where('choice_id', !null)->count() == $quizProgress->deck->cards->count() &&
        !$quizProgress->isCompleted)
        <button class="quiz-btn quiz-btn-primary" wire:click="finishQuiz">Finish Quiz</button>
        @endif
        <button class="quiz-btn quiz-btn-secondary" wire:click="nextQuizCard" {{ $currentIndex>=
            $quizProgress->deck->cards->count() - 1 ? 'disabled' : '' }}>
            Next
        </button>
    </footer>

    @else
    <h2 class="quiz-completed-title">Quiz Completed</h2>
    <p class="quiz-completed-description">You have completed the quiz for this deck. You scored
        {{$quizProgress->correctItems}}/{{$quizProgress->totalItems}}</p>
    <button class="quiz-btn quiz-btn-primary" wire:click="retakeQuiz">Retake Quiz</button>
    <div class="deck-view-grid">
        @foreach ($quizzes as $quiz)
        <div class="deck-view-card {{ $quiz->isCorrect ? 'quiz-assesment-choice-correct' : 'quiz-assesment-choice-wrong' }}"
            wire:key="{{ $quiz->id }}">
            <div class="deck-view-description">
                <p>Question: {{ $quiz->card->question }}</p>
                <p>Your answer: {{ $quiz->choice->choice }}</p>
                <p>Correct answer: {{ $quiz->card->choices->where('isCorrect', 1)->first()->choice }}</p>
            </div>
        </div>
        @endforeach
    </div>

    @endif

</div>