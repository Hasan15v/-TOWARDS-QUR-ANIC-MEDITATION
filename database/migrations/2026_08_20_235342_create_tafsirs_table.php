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
        Schema::create('tafsirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surah_id')
            ->constrained('surhes')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->foreignId('start_verse_id')
            ->constrained('verses')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->foreignId('end_verse_id')
            ->constrained('verses')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->enum('level', ['beginner', 'advanced']);
            $table->longText('text');
            
            $table->index(['surah_id', 'level']);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tafsirs');
    }
};
