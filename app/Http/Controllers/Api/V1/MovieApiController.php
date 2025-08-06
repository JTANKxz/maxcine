<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AutoEmbedUrls;
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
        $contentType = $movie->content_type;

        // Gêneros
        $genres = $movie->genres;

        // Relacionados
        $relatedMovies = Movie::whereHas('genres', function ($query) use ($genres) {
            $query->whereIn('genres.id', $genres->pluck('id'));
        })
            ->where('id', '!=', $movie->id)
            ->with('genres')
            ->latest()
            ->limit(10)
            ->get();

        // AutoEmbed: links gerados dinamicamente
        $autoEmbeds = AutoEmbedUrls::where('active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($embed) use ($movie) {
                $idType = null;
                $url = $embed->url;

                if (str_contains($url, '{imdb_id}')) {
                    $url = str_replace('{imdb_id}', $movie->imdb_id, $url);
                    $idType = 'imdb';
                } elseif (str_contains($url, '{tmdb_id}')) {
                    $url = str_replace('{tmdb_id}', $movie->tmdb_id, $url);
                    $idType = 'tmdb';
                }

                return [
                    'id' => null,
                    'movie_id' => $movie->id,
                    'name' => $embed->name,
                    'quality' => $embed->quality,
                    'order' => $embed->order,
                    'url' => $url,
                    'type' => $embed->type,
                    'player_sub' => $embed->player_sub,
                    'auto' => true,
                    'id_type' => $idType, // 👈 Aqui vai indicar qual ID foi usado
                ];
            });

        // Junta os dois tipos de link
        $allLinks = $links->toArray(); // links reais do banco
        $allLinks = array_merge($allLinks, $autoEmbeds->toArray());

        return response()->json([
            'movie' => $movie,
            'links' => $allLinks,
            'related' => $relatedMovies,
            'content_type' => $contentType
        ]);
    }
}
