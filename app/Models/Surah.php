<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Surah extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'surhes';

    protected $fillable = [
       'number',
       'name_ar',
       'name_en',
       'verses_count',
       'slug',
    ];
    //

    public function verses():HasMany
    {
     return $this->hasMany(Verse::class);
    }

    public function tafsir():HasMany
    {
     return $this->hasMany(Tafsir::class);
    }

    public function readingprogress():HasMany
    {
     return $this->hasMany(ReadingProgress::class);
    }
}
