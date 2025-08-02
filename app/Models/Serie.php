<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'tmdb_id',
        'imdb_id',
        'year',
        'overview',
        'poster_url',
        'backdrop_url',
        'rating',
        'content_type', // Adicionado aqui
    ];

    public function genres()
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function episodes()
    {
        return $this->hasManyThrough(
            \App\Models\Episode::class,
            \App\Models\Season::class,
            'serie_id',     // Foreign key na tabela seasons
            'season_id',    // Foreign key na tabela episodes
            'id',           // Local key na tabela series
            'id'            // Local key na tabela seasons
        );
    }

    public function watchlistedBy()
    {
        return $this->morphToMany(User::class, 'content', 'watchlist')->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($serie) {
            $serie->content_type = 'serie';
        });
    }
}
