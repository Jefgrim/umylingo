<?php

use App\Models\Deck;
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
        Schema::create('deck_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Deck::class);
            $table->foreignIdFor(User::class);
            $table->integer('cardLearnIndex')->default(0);
            $table->integer('cardQuizIndex')->default(0);
            $table->integer('score')->default(0);
            $table->boolean('isQuizStarted')->default(0);
            $table->boolean('isLearningStarted')->default(0);
            $table->boolean('isLearningCompleted')->default(0);
            $table->boolean('isQuizCompleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
