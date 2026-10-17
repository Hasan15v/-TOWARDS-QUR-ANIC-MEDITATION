<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable; 
class Verse extends Model
{
    use HasFactory, Notifiable;

      protected $fillable = [
        'surah_id',
        'verse_number',
        'arabic_text',
        
    ];

  public function surahs(): BelongsTo
  {
    return $this->belongsTo(Surah::class);
  }

  // singular alias - prefer using $verse->surah in views
  public function surah(): BelongsTo
  {
    return $this->belongsTo(Surah::class);
  }

   public function bookmark(): HasMany
  {
    return $this->hasMany(Bookmark::class);
  }

   public function readingprogress(): HasMany
  {
    return $this->hasMany(ReadingProgress::class);
  }
}
