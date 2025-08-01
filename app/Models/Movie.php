<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'tmdb_id',
        'imdb_id', // Adicionado aqui
        'title',
        'slug',
        'year',
        'overview',
        'poster_url',
        'backdrop_url',
        'runtime',
        'rating',
    ];

    public function genres()
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }

    public function playLinks()
    {
        return $this->hasMany(MoviePlayLink::class);
    }

    public function watchlistedBy()
    {
        return $this->morphToMany(User::class, 'content', 'watchlist')->withTimestamps();
    }
}
