<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tafsir_videos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tafsir_id')
                ->constrained('tafsirs')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('title');

            $table->text('video_url');

            $table->text('description')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['tafsir_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tafsir_videos');
    }
};