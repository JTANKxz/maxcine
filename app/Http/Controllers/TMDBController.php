<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class TMDBController extends Controller
{
    private $apiKey = 'edcd52275afd8b8c152c82f1ce3933a2';
    public function index()
    {
        return view('dashboard.tmdb.index');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type', 'multi'); // movie, tv, multi
        $genre = $request->input('genre');
        $year = $request->input('year');
        $page = $request->input('page', 1);

        $results = [];
        $total_pages = 1;

        if (!$query && !$genre && !$year) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Por favor, informe um termo, selecione um gênero ou um ano.'], 422);
            }
            return redirect()->back()->with('error', 'Por favor, informe um termo, selecione um gênero ou um ano.');
        }

        if (!$query) {
            $discoverType = in_array($type, ['movie', 'tv']) ? $type : 'movie';

            $response = Http::get("https://api.themoviedb.org/3/discover/{$discoverType}", [
                'api_key' => $this->apiKey,
                'language' => 'pt-BR',
                'sort_by' => 'popularity.desc',
                'with_genres' => $genre,
                'primary_release_year' => $discoverType === 'movie' ? $year : null,
                'first_air_date_year' => $discoverType === 'tv' ? $year : null,
                'page' => $page,
            ]);
        } else {
            $endpoint = $type === 'multi' ? 'search/multi' : "search/{$type}";
            $response = Http::get("https://api.themoviedb.org/3/{$endpoint}", [
                'api_key' => $this->apiKey,
                'language' => 'pt-BR',
                'query' => $query,
                'include_adult' => false,
                'page' => $page,
            ]);
        }

        if ($response->successful()) {
            $json = $response->json();
            $results = $json['results'] ?? [];
            $total_pages = $json['total_pages'] ?? 1;

            // FILTRO por gênero e ano se estiver usando search
            if ($query && ($genre || $year)) {
                $results = array_filter($results, function ($item) use ($type, $genre, $year) {
                    $mediaType = $item['media_type'] ?? $type;

                    if (!in_array($mediaType, ['movie', 'tv'])) {
                        return false;
                    }

                    if ($genre && (!isset($item['genre_ids']) || !in_array((int) $genre, $item['genre_ids']))) {
                        return false;
                    }

                    if ($year) {
                        $dateField = $mediaType === 'tv' ? 'first_air_date' : 'release_date';
                        if (!isset($item[$dateField]) || substr($item[$dateField], 0, 4) != $year) {
                            return false;
                        }
                    }

                    return true;
                });
            }

            // Buscar posters em português
            foreach ($results as &$item) {
                $mediaType = $item['media_type'] ?? $type;

                if (!in_array($mediaType, ['movie', 'tv'])) continue;

                $imgRes = Http::get("https://api.themoviedb.org/3/{$mediaType}/{$item['id']}/images", [
                    'api_key' => $this->apiKey,
                ]);

                if ($imgRes->successful()) {
                    $posters = collect($imgRes->json()['posters']);
                    $poster = $posters->first(fn($img) => $img['iso_639_1'] === 'pt-BR')
                        ?? $posters->first(fn($img) => $img['iso_639_1'] === 'pt')
                        ?? $posters->first(fn($img) => $img['iso_639_1'] === null)
                        ?? $posters->first();
                    if ($poster) {
                        $item['poster_path'] = $poster['file_path'];
                    }
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'results' => array_values($results),
                'page' => (int)$page,
                'total_pages' => $total_pages,
            ]);
        }

        return view('dashboard.tmdb.index', [
            'results' => $results,
            'query' => $query,
            'type' => $type,
            'genre' => $genre,
            'year' => $year,
            'page' => $page,
            'total_pages' => $total_pages,
        ]);
    }


    public function import(Request $request)
    {
        $tmdbId = $request->tmdb_id;

        $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}", [
            'api_key' => $this->apiKey,
            'language' => 'pt-BR'
        ]);

        if (!$response->successful()) {
            return back()->with('error', 'Erro ao buscar dados do filme.');
        }

        $data = $response->json();

        // Busca imagens com preferência por português
        $imagesResponse = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}/images", [
            'api_key' => $this->apiKey,
        ]);

        $poster = null;

        if ($imagesResponse->successful()) {
            $posters = collect($imagesResponse->json()['posters']);

            // Prioridade: pt-BR > pt > null > qualquer
            $poster = $posters->first(fn($img) => $img['iso_639_1'] === 'pt-BR')
                ?? $posters->first(fn($img) => $img['iso_639_1'] === 'pt')
                ?? $posters->first(fn($img) => $img['iso_639_1'] === null)
                ?? $posters->first();
        }

        // Usa o path do poster escolhido ou o que veio no dado principal
        $posterPath = $poster['file_path'] ?? $data['poster_path'];


        $movie = Movie::updateOrCreate(
            ['tmdb_id' => $data['id']],
            [
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'year' => substr($data['release_date'] ?? '', 0, 4),
                'overview' => $data['overview'],
                'poster_url' => $posterPath ? 'https://image.tmdb.org/t/p/w500' . $posterPath : null,

                'backdrop_url' => 'https://image.tmdb.org/t/p/w1280' . $data['backdrop_path'],
                'runtime' => $data['runtime'],
                'rating' => $data['vote_average'],
                'imdb_id' => $data['imdb_id'],
            ]
        );


        // Importa e associa os gêneros
        // Importa e associa os gêneros ao filme
        $genreIds = [];

        foreach ($data['genres'] as $genreData) {
            $genre = Genre::firstOrCreate(
                ['tmdb_id' => $genreData['id']],
                ['name' => $genreData['name']]
            );

            $genreIds[] = $genre->id;
        }

        // Associa os gêneros de forma polimórfica ao movie
        $movie->genres()->sync($genreIds);


        return response()->json([
            'message' => 'Filme importado com sucesso!',
            'movie_id' => $movie->id,
        ]);
    }

    public function importSerie(Request $request)
    {
        $tmdbId = $request->tmdb_id;

        $response = Http::get("https://api.themoviedb.org/3/tv/{$tmdbId}", [
            'api_key' => $this->apiKey,
            'language' => 'pt-BR'
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'Erro ao buscar dados da série.',
            ], 500);
        }

        $data = $response->json();

        // Requisição para IDs externos
        $externalResponse = Http::get("https://api.themoviedb.org/3/tv/{$tmdbId}/external_ids", [
            'api_key' => $this->apiKey,
        ]);

        $imdbId = null;
        if ($externalResponse->successful()) {
            $externalData = $externalResponse->json();
            $imdbId = $externalData['imdb_id'] ?? null;
        }

        $serie = Serie::updateOrCreate(
            ['tmdb_id' => $data['id']],
            [
                'title' => $data['name'],
                'slug' => Str::slug($data['name']),
                'year' => substr($data['first_air_date'] ?? '', 0, 4),
                'overview' => $data['overview'],
                'poster_url' => 'https://image.tmdb.org/t/p/w500' . $data['poster_path'],
                'backdrop_url' => 'https://image.tmdb.org/t/p/w1280' . $data['backdrop_path'],
                'rating' => $data['vote_average'],
                'imdb_id' => $imdbId,
            ]
        );

        // Importar e sincronizar gêneros (polimórfico)
        $genreIds = [];
        foreach ($data['genres'] as $genreData) {
            $genre = Genre::firstOrCreate(
                ['tmdb_id' => $genreData['id']],
                ['name' => $genreData['name']]
            );
            $genreIds[] = $genre->id;
        }

        $serie->genres()->sync($genreIds);

        return response()->json([
            'message' => 'Série importada com sucesso!',
            'serie_id' => $serie->id,
            'prompt_seasons' => true
        ]);
    }

    public function importSeasons(Serie $serie)
    {
        $tmdbId = $serie->tmdb_id;
        $apiKey = 'edcd52275afd8b8c152c82f1ce3933a2'; // ou use config('services.tmdb.key')

        $response = Http::get("https://api.themoviedb.org/3/tv/{$tmdbId}?api_key={$apiKey}&language=pt-BR");
        if ($response->failed()) {
            return redirect()->back()->with('error', 'Erro ao buscar temporadas.');
        }

        $data = $response->json();
        foreach ($data['seasons'] as $seasonData) {
            if ($seasonData['season_number'] == 0) continue;

            $serie->seasons()->updateOrCreate(
                ['season_number' => $seasonData['season_number']],
                [
                    'name' => $seasonData['name'],
                    'overview' => $seasonData['overview'],
                    'poster_path' => $seasonData['poster_path'],
                    'tmdb_id' => $seasonData['id'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Temporadas importadas com sucesso!');
    }



    public function importEpisodes(Request $request, Serie $series)
    {
        foreach ($series->seasons as $season) {
            $seasonData = Http::get("https://api.themoviedb.org/3/tv/{$series->tmdb_id}/season/{$season->season_number}", [
                'api_key' => $this->apiKey,
                'language' => 'pt-BR'
            ])->json();

            foreach ($seasonData['episodes'] as $ep) {
                $season->episodes()->updateOrCreate(
                    ['episode_number' => $ep['episode_number']],
                    [
                        'name' => $ep['name'],
                        'overview' => $ep['overview'],
                        'still_url' => $ep['still_path'] ? 'https://image.tmdb.org/t/p/w500' . $ep['still_path'] : null,
                        'runtime' => $ep['runtime']
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Episódios importados com sucesso!');
    }
}
