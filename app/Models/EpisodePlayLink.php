<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpisodePlayLink extends Model
{
    protected $fillable = [
        'episode_id',  // Adicione este campo aqui
        'name',
        'quality',
        'order',
        'url',
        'type',
        'player_sub',
        // outros campos que você tenha...
    ];
    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }
}
