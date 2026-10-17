<?php

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
        Schema::create('reading_progress', function (Blueprint $table) {
            $table->id();
          
    $table->foreignId('user_id')
          ->constrained('users')
          ->cascadeOnDelete()
          ->cascadeOnUpdate();

    $table->foreignId('surah_id')
          ->constrained('surhes')
          ->cascadeOnDelete()
          ->cascadeOnUpdate();

    $table->foreignId('verse_id')
          ->constrained('verses')
          ->cascadeOnDelete()
          ->cascadeOnUpdate();
            $table->timestamps();

     $table->unique(['user_id', 'surah_id']);
        
     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_progress');
    }
};
