<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieApiController extends Controller
{
    public function index()
    {
        $movies = Movie::with('genres')
            ->orderByDesc('id')
            ->get();

        return response()->json($movies);
    }

    public function show($id)
    {
        $movie = Movie::with('genres')->findOrFail($id);
        $links = $movie->playLinks()->get();
        $relatedMovies = Movie::whereHas('genres', function ($query) use ($movie) {
            $query->whereIn('id', $movie->genres->pluck('id'));
        })->where('id', '!=', $movie->id)->get();

        return response()->json([
            'movie' => $movie,
            'links' => $links,
            'related' => $relatedMovies
        ]);
    }
}
