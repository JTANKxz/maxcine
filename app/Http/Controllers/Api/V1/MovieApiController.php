<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\MoviePlayLink;
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
        $links = MoviePlayLink::where('movie_id', $movie->id)->get();

        // Obtem os gêneros do filme atual
        $genres = $movie->genres;

        // Busca filmes relacionados por gênero
        $relatedMovies = Movie::whereHas('genres', function ($query) use ($genres) {
            $query->whereIn('genres.id', $genres->pluck('id'));
        })
            ->where('id', '!=', $movie->id)
            ->with('genres')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'movie' => $movie,
            'links' => $links,
            'related' => $relatedMovies,
        ]);
    }
}
