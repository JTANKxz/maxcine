<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TVChannelLink extends Model
{
    protected $table = 'tv_channel_links';

    protected $fillable = ['tv_channel_id', 'name', 'url', 'type', 'player_sub', 'order'];

    public function channel()
    {
        return $this->belongsTo(TVChannel::class, 'tv_channel_id');
    }
}
