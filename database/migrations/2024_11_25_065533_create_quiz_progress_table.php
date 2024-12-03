<?php

use App\Models\Deck;
use App\Models\LearnProgress;
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
        Schema::create('quiz_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Deck::class);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(LearnProgress::class);
            $table->integer('currentIndex')->default(0);
            $table->boolean('isStarted')->default(0);
            $table->boolean('isCompleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_progress');
    }
};
