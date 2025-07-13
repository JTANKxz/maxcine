<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoviePlayLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'name',
        'quality',
        'order',
        'url',
        'type',
        'player_sub',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
