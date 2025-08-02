<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Serie;
use App\Models\MoviePlayLink;
use Illuminate\Http\Request;

class SerieApiController extends Controller
{
    public function index()
    {
        $series = Serie::with('genres')
            ->orderByDesc('id')
            ->get();

        return response()->json($series);
    }

    public function show($id)
    {
        $serie = Serie::with('genres')->findOrFail($id);
        $seasons = $serie->seasons()
            ->with(['episodes' => function ($query) {
                $query->orderBy('episode_number')->with('playLinks');
            }])
            ->orderBy('season_number')
            ->get();
        $contentType = $serie->content_type;
        // Obtem os gêneros da série atual
        $genres = $serie->genres;

        // Busca séries relacionadas por gênero
        $relatedSeries = Serie::whereHas('genres', function ($query) use ($genres) {
            $query->whereIn('genres.id', $genres->pluck('id'));
        })
            ->where('id', '!=', $serie->id)
            ->with('genres')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'serie' => $serie,
            'seasons' => $seasons,
            'related' => $relatedSeries,
            'content_type' => $contentType
        ]);
    }
}
