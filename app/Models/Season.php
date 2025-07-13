<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'serie_id',
        'season_number',
        'name',
        'poster_url',
        'air_date',
    ];

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
