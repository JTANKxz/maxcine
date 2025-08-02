<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Order;
use App\Models\Season;
use App\Models\Serie;
use App\Models\Slider;
use App\Models\TVChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function movies()
    {
        $movies = Movie::orderByDesc('id')->paginate(20);
        return view('dashboard.movies.index', ['movies' => $movies]);
    }

    public function series()
    {
        $series = Serie::orderByDesc('id')->paginate(10);
        return view('dashboard.series.index', ['series' => $series]);
    }

    public function seasons(Serie $serie)
    {
        // ❌ Isso retorna uma Collection simples, que não tem ->links()
        // $seasons = $serie->seasons()->orderBy('season_number')->get();

        // ✅ Use paginação
        $seasons = $serie->seasons()->orderBy('season_number')->paginate(10);

        return view('dashboard.series.seasons', compact('serie', 'seasons'));
    }

    public function episodes(Serie $serie, Season $season)
    {
        $episodes = $season->episodes()->orderBy('episode_number')->paginate(10);

        return view('dashboard.series.episodes', compact('serie', 'season', 'episodes'));
    }

    public function importSeasons(Serie $serie)
    {
        $tmdbId = $serie->tmdb_id; // ajuste conforme seu campo de ID do TMDB
        $apiKey = 'edcd52275afd8b8c152c82f1ce3933a2'; // ou use config('services.tmdb.key')

        $response = Http::get("https://api.themoviedb.org/3/tv/{$tmdbId}?api_key={$apiKey}&language=pt-BR");

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Erro ao buscar temporadas da API do TMDB.');
        }

        $data = $response->json();

        foreach ($data['seasons'] as $seasonData) {
            if ($seasonData['season_number'] == 0) continue; // ignora especiais

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

    public function importEpisodes(Serie $serie)
    {
        $apiKey = 'edcd52275afd8b8c152c82f1ce3933a2'; // ou config('services.tmdb.key')

        foreach ($serie->seasons as $season) {
            if ($season->season_number === 0) continue;

            $response = Http::get("https://api.themoviedb.org/3/tv/{$serie->tmdb_id}/season/{$season->season_number}", [
                'api_key' => $apiKey,
                'language' => 'pt-BR',
            ]);

            if ($response->failed()) continue;

            $data = $response->json();

            foreach ($data['episodes'] as $ep) {
                $season->episodes()->updateOrCreate(
                    ['episode_number' => $ep['episode_number']],
                    [
                        'name' => $ep['name'],
                        'overview' => $ep['overview'],
                        'still_url' => isset($ep['still_path']) ? 'https://image.tmdb.org/t/p/w500' . $ep['still_path'] : null,
                        'runtime' => $ep['runtime'] ?? null,
                        'tmdb_id' => $ep['id'],
                        'air_date' => $ep['air_date'] ?? null,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Episódios importados com sucesso!');
    }

    public function sliders()
    {
        // Aqui você pode implementar a lógica para buscar e exibir os sliders
        // Exemplo:
        $sliders = Slider::all();
        return view('dashboard.sliders.index', compact('sliders'));
    }

    public function slidersCreate()
    {
        // Aqui você pode implementar a lógica para criar um novo slider
        // Exemplo:
        return view('dashboard.sliders.create');
    }

    public function slidersStore(Request $request)
    {
        // Aqui você pode implementar a lógica para armazenar um novo slider
        // Exemplo:
        $slider = Slider::create($request->all());
        return redirect()->route('sliders.index')->with('success', 'Slider criado com sucesso!');
    }

    public function destroySlider(Slider $slider)
    {
        try {
            $slider->delete();
            return redirect()->route('sliders.index')->with('success', 'Slider deletado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('sliders.index')->with('error', 'Failed to delete slider.');
        }
    }

    public function search(Request $request)
    {
        $type = $request->input('type');
        $query = $request->input('query');

        if ($type === 'movie') {
            // Busca na tabela movies, trazendo slug, rating, etc
            $results = Movie::where('title', 'like', "%{$query}%")
                ->select('id', 'slug', 'title', 'year', 'runtime', 'backdrop_url', 'rating')
                ->get();
        } elseif ($type === 'serie') {
            $results = Serie::withCount('seasons')
                ->where('title', 'like', "%{$query}%")
                ->select('id', 'slug', 'title', 'year', 'backdrop_url', 'rating')
                ->get()
                ->map(function ($serie) {
                    $serie->season_count = $serie->seasons_count;
                    return $serie;
                });
        } else {
            $results = collect();
        }

        return response()->json($results);
    }

    public function searchMovies(Request $request)
    {
        $query = $request->input('q');

        $results = Movie::where('title', 'like', "%{$query}%")
            ->select('id', 'title', 'year', 'poster_url')
            ->limit(15)
            ->get();

        return response()->json($results);
    }

    public function searchSeries(Request $request)
    {
        $query = $request->input('q');

        $results = Serie::where('title', 'like', "%{$query}%")
            ->select('id', 'title', 'year', 'poster_url')
            ->limit(15)
            ->get();

        return response()->json($results);
    }

    public function tvChannels()
    {
        $channels = TVChannel::all();
        return view('dashboard.tv.index', compact('channels'));
    }

    public function createChannel()
    {
        return view('dashboard.tv.create');
    }

    public function storeChannel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        try {
            $slug = Str::slug($request->name);

            // Garante unicidade do slug se necessário
            $originalSlug = $slug;
            $i = 1;
            while (TVChannel::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }

            $channel = TVChannel::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'image_url' => $request->image_url,
            ]);

            return redirect()->route('channels.index')->with('success', 'Canal criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create channel.');
        }
    }

    public function editChannel(TVChannel $channel)
    {
        return view('dashboard.tv.edit', compact('channel'));
    }

    public function updateChannel(Request $request, TVChannel $channel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        try {
            $slug = Str::slug($request->name);

            // Garante unicidade do slug se necessário
            $originalSlug = $slug;
            $i = 1;
            while (TVChannel::where('slug', $slug)->where('id', '!=', $channel->id)->exists()) {
                $slug = $originalSlug . '-' . $i++;
            }

            $channel->update([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'image_url' => $request->image_url,
            ]);

            return redirect()->route('channels.index')->with('success', 'Canal atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update channel.');
        }
    }

    public function orders()
    {
        $orders = Order::with('user')->orderByDesc('id')->paginate(20);
        return view('dashboard.orders.index', compact('orders'));
    }

    public function destroyChannel(TVChannel $channel)
    {
        try {
            $channel->delete();
            return redirect()->route('channels.index')->with('success', 'Canal deletado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('channels.index')->with('error', 'Failed to delete channel.');
        }
    }

    public function destroy(Movie $movie)
    {
        try {
            $movie->delete();
            return redirect()->route('movies.index')->with('success', 'Filme deletado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('movies.index')->with('error', 'Failed to delete movie.');
        }
    }
}
