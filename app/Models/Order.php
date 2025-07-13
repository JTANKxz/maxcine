<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'type', 'tmdb_id', 'title', 'poster_url', 'year', 'status', 'total', 'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

