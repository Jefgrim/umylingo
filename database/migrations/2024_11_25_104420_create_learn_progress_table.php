<?php

use App\Models\Deck;
use App\Models\DeckProgress;
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
        Schema::create('learn_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Deck::class);
            $table->foreignIdFor(User::class);
            $table->integer('currentIndex')->default(0);
            $table->boolean('isStarted')->default(0);
            $table->boolean('isCompleted')->default(0);
            $table->timestamp('startedAt')->nullable()->default(null);
            $table->timestamp('completedAt')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learn_progress');
    }
};
