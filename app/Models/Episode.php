<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'episode_number',
        'name',
        'overview',
        'still_url',
        'runtime',
        'tmdb_id',
        'air_date',
        'season_id',
    ];


    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function playLinks()
    {
        return $this->hasMany(EpisodePlayLink::class);
    }

    
}
