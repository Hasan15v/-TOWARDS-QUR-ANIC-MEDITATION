<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TafsirVideo;

class Tafsir extends Model
{
    use HasFactory;

    protected $table = 'tafsirs';

    protected $fillable = [
        'surah_id',
        'start_verse_id',
        'end_verse_id',
        'text',
        'level',
    ];

    public function surah()
    {
        return $this->belongsTo(Surah::class);
    }

    public function startVerse()
    {
        return $this->belongsTo(Verse::class, 'start_verse_id');
    }

    public function endVerse()
    {
        return $this->belongsTo(Verse::class, 'end_verse_id');
    }

    public function videos()
{
    return $this->hasMany(TafsirVideo::class)->orderBy('sort_order');
}
}
