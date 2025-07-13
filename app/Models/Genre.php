<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genre extends Model
{
    protected $fillable = [
        'tmdb_id',
        'name',
        'slug', // importante garantir que slug esteja aqui se você usar mass assignment
    ];

    protected static function booted()
    {
        static::saving(function ($genre) {
            if (empty($genre->slug) && !empty($genre->name)) {
                $baseSlug = Str::slug($genre->name);
                $slug = $baseSlug;
                $i = 1;

                // Garante que o slug seja único
                while (Genre::where('slug', $slug)->where('id', '!=', $genre->id)->exists()) {
                    $slug = $baseSlug . '-' . $i++;
                }

                $genre->slug = $slug;
            }
        });
    }

    public function movies()
    {
        return $this->morphedByMany(Movie::class, 'genreable');
    }

    public function series()
    {
        return $this->morphedByMany(Serie::class, 'genreable');
    }
}
