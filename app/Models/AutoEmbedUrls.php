<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoEmbedUrls extends Model
{
    protected $fillable = [
        'name', 'type', 'base_url', 'player_sub', 'quality', 'order'
    ];
}
