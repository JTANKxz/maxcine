<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'type', // 'movie' ou 'serie'
        'content_id', // id do filme ou série
        'title',
        'slug',
        'backdrop_url',
        'year',
        'runtime',
        'rating',
        'seasons_count',
    ];

    public function content()
    {
        return $this->morphTo(null, 'type', 'content_id', 'id');
    }

    // Alternativamente, se não quiser morph, pode fazer isso manualmente:
    public function movie()
    {
        return $this->belongsTo(Movie::class, 'content_id');
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class, 'content_id');
    }
}
