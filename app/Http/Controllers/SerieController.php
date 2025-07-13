<?php

namespace App\Http\Controllers;

use App\Models\Serie;
use App\Models\Season;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index()
    {
        $series = Serie::orderByDesc('id')->paginate(24);
        return view('public.series.index', ['series' => $series]);
    }

    public function show($slug)
    {
        $serie = Serie::withCount('seasons')->where('slug', $slug)->firstOrFail();

        // Carrega as temporadas com seus episódios ordenados e links
        $seasons = $serie->seasons()
            ->with(['episodes' => function ($query) {
                $query->orderBy('episode_number')->with('playLinks');
            }])
            ->orderBy('season_number')
            ->get();

        // Para cada episódio, adicionar links autoembed customizados
        foreach ($seasons as $season) {
            foreach ($season->episodes as $episode) {
                // Links já carregados do banco
                $links = $episode->playLinks;

                // ID da série no TMDB
                $tmdbId = $serie->tmdb_id;
                $imdbId = $serie->imdb_id;
                $seasonNumber = $season->season_number;
                $episodeNumber = $episode->episode_number;

                // Gerando URL autoembed dinamicamente
                $autoembedLinks = [
                    (object)[
                        'url' => "https://embedder.net/e/{$episode->tmdb_id}",
                        'type' => 'embed',
                        'quality' => 'HD',
                        'order' => 0,
                        'name' => 'OPÇÃO 1',
                    ],
                    (object)[
                        'url' => "https://embed.embedplayer.site/tv/{$tmdbId}/{$seasonNumber}/{$episodeNumber}/dub",
                        'type' => 'embed',
                        'quality' => 'HD',
                        'order' => 0,
                        'name' => 'OPÇÃO 2',
                    ],
                    (object)[
                        'url' => "https://embed.warezcdn.com/serie/{$imdbId}/{$seasonNumber}/{$episodeNumber}",
                        'type' => 'embed',
                        'quality' => 'HD',
                        'order' => 0,
                        'name' => 'OPÇÃO 3',
                    ],
                    (object)[
                        'url' => "https://superflixapi.lat/serie/{$tmdbId}/{$seasonNumber}/{$episodeNumber}",
                        'type' => 'embed',
                        'quality' => 'HD',
                        'order' => 0,
                        'name' => 'OPÇÃO 4',
                    ],
                ];

                // Prepend para manter ordem correta (links autoembed antes dos outros)
                foreach (array_reverse($autoembedLinks) as $autoembed) {
                    $links->prepend($autoembed);
                }

                // Substitui o relacionamento playLinks para incluir autoembed
                $episode->setRelation('playLinks', $links);
            }
        }

        $genres = $serie->genres;

        // Conteúdo relacionado: outras séries que compartilham pelo menos um gênero
        $relatedSeries = Serie::whereHas('genres', function ($query) use ($genres) {
            $query->whereIn('genres.id', $genres->pluck('id'));
        })
            ->where('series.id', '!=', $serie->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('public.series.show', compact('serie', 'genres', 'seasons', 'relatedSeries'));
    }
}
