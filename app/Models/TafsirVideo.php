<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TafsirVideo extends Model
{
     protected $fillable = [
        'tafsir_id',
        'title',
        'video_url',
        'description',
        'sort_order',
    ];

    public function tafsir()
    {
        return $this->belongsTo(Tafsir::class);
    }
}
