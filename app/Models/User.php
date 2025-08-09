<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

// Importar a trait do Sanctum para API tokens
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // Use a trait HasApiTokens junto com as outras
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function watchlistMovies(): MorphToMany
    {
        return $this->morphedByMany(Movie::class, 'content', 'watchlist')->withTimestamps();
    }

    public function watchlistSeries(): MorphToMany
    {
        return $this->morphedByMany(Serie::class, 'content', 'watchlist')->withTimestamps();
    }

    /**
     * Lista unificada de todos os conteúdos salvos
     */
    public function watchlistItems()
    {
        return collect()
            ->merge($this->watchlistMovies)
            ->merge($this->watchlistSeries)
            ->sortByDesc('pivot.created_at');
    }
}
