<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable; 
#[Fillable(['user_id', 'surah_id', 'verse_id'])]
class ReadingProgress extends Model
{
    use HasFactory, Notifiable;
protected $fillable = [
        'user_id',
        'surah_id',
        'verse_id',
    ];

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }

    public function verse(): BelongsTo
    {
        return $this->belongsTo(Verse::class);
    }
    //
}
