<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TVChannel extends Model
{
    protected $table = 'tv_channels';
    protected $fillable = ['name', 'slug', 'description', 'image_url'];

    public function links()
    {
        return $this->hasMany(TVChannelLink::class, 'tv_channel_id')->orderBy('order');
    }
}
