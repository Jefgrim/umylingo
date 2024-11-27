<?php

use App\Models\Card;
use App\Models\Choice;
use App\Models\QuizProgress;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(QuizProgress::class);
            $table->foreignIdFor(Card::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(model: Choice::class)->nullable();
            $table->boolean('isAnswered')->default(0);
            $table->boolean('isCorrect')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz');
    }
};
