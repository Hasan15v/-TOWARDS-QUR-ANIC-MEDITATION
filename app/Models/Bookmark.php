<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable; 
#[Fillable(['user_id', 'verse_id'])]

class Bookmark extends Model
{
    use HasFactory, Notifiable;

    
 protected $fillable = [
    'user_id',
    'start_verse_id',
    'end_verse_id',
];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

   public function startVerse()
{
    return $this->belongsTo(Verse::class, 'start_verse_id');
}

public function endVerse()
{
    return $this->belongsTo(Verse::class, 'end_verse_id');
}
}
