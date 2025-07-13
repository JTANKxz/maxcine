<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\MoviePlayLink;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::orderByDesc('id')->paginate(24);
        return view('public.movies.index', compact('movies'));
    }

    public function show($slug)
    {
        $movie = Movie::where('slug', $slug)->firstOrFail();
        $genres = $movie->genres;
        $links = MoviePlayLink::where('movie_id', $movie->id)->get();
        $autoembedLinks = [
            [
                'url' => "https://embedder.net/e/{$movie->tmdb_id}",
                'type' => 'embed',
                'quality' => 'HD',
                'order' => 0,
                'name' => 'OPÇÃO 1',
            ],
            [
                'url' => "https://embed.embedplayer.site/{$movie->imdb_id}",
                'type' => 'embed',
                'quality' => 'HD',
                'order' => 0,
                'name' => 'OPÇÃO 2',
            ],
            [
                'url' => "https://embed.warezcdn.com/filme/{$movie->imdb_id}",
                'type' => 'embed',
                'quality' => 'HD',
                'order' => 0,
                'name' => 'OPÇÃO 3',
            ],
            [
                'url' => "https://superflixapi.lat/filme/{$movie->imdb_id}",
                'type' => 'embed',
                'quality' => 'HD',
                'order' => 0,
                'name' => 'OPÇÃO 4',
            ],
        ];

        // Converte pra objeto
        foreach (array_reverse($autoembedLinks) as $autoembed) {
            $links->prepend((object) $autoembed);
        }

        // Conteúdo relacionado: filmes que compartilham pelo menos um gênero
        $relatedMovies = Movie::whereHas('genres', function ($query) use ($genres) {
            $query->whereIn('genres.id', $genres->pluck('id'));
        })
            ->where('movies.id', '!=', $movie->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('public.movies.show', compact('movie', 'genres', 'links', 'relatedMovies'));
    }

    public function showByTmdb($id)
    {
        $movie = Movie::where('tmdb_id', $id)->first();

        if (!$movie) {
            return response()->view('public.notfound', [], 404);
            // ou
            // return view('errors.notfound')->setStatusCode(404);
        }

        $genres = $movie->genres;
        $links = MoviePlayLink::where('movie_id', $movie->id)->get();

        $autoembedLinks = [
            [
                'url' => "https://embedder.net/e/{$movie->tmdb_id}",
                'type' => 'embed',
                'quality' => 'HD',
                'order' => 0,
                'name' => 'OPÇÃO 1',
            ],
            [
                'url' => "https://embed.embedplayer.site/{$movie->imdb_id}",
                'type' => 'embed',
                'quality' => 'HD',
                'order' => 0,
                'name' => 'OPÇÃO 2',
            ],
            [
                'url' => "https://embed.warezcdn.com/filme/{$movie->imdb_id}",
                'type' => 'embed',
                'quality' => 'HD',
                'order' => 0,
                'name' => 'OPÇÃO 3',
            ],
            [
                'url' => "https://superflixapi.lat/filme/{$movie->imdb_id}",
                'type' => 'embed',
                'quality' => 'HD',
                'order' => 0,
                'name' => 'OPÇÃO 4',
            ]
        ];

        foreach (array_reverse($autoembedLinks) as $autoembed) {
            $links->prepend((object) $autoembed);
        }

        $relatedMovies = Movie::whereHas('genres', function ($query) use ($genres) {
            $query->whereIn('genres.id', $genres->pluck('id'));
        })
            ->where('movies.id', '!=', $movie->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('public.movies.show', compact('movie', 'genres', 'links', 'relatedMovies'));
    }


    public function create()
    {
        $genres = Genre::all();
        return view('dashboard.movies.create', ['genres' => $genres]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'tmdb_id' => 'nullable|integer',
            'year' => 'nullable|integer',
            'overview' => 'nullable|string',
            'poster_url' => 'nullable|url',
            'backdrop_url' => 'nullable|url',
            'runtime' => 'nullable|integer',
            'rating' => 'nullable|numeric',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
        ]);

        $movie = Movie::create([
            'title' => $validated['title'],
            'tmdb_id' => $validated['tmdb_id'] ?? null,
            'year' => $validated['year'] ?? null,
            'overview' => $validated['overview'] ?? null,
            'poster_url' => $validated['poster_url'] ?? null,
            'backdrop_url' => $validated['backdrop_url'] ?? null,
            'runtime' => $validated['runtime'] ?? null,
            'rating' => $validated['rating'] ?? null,
        ]);

        if (!empty($validated['genres'])) {
            $movie->genres()->sync($validated['genres']);
        }

        return redirect()->route('movies.index')->with('success', 'Filme criado com sucesso!');
    }

    public function edit(Movie $movie)
    {
        $genres = Genre::all();
        $selectedGenres = $movie->genres->pluck('id')->toArray();
        return view('dashboard.movies.edit', ['movie' => $movie, 'genres' => $genres, 'selectedGenres' => $selectedGenres,]);
    }

    public function update(Request $request, Movie $movie)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'tmdb_id' => 'nullable|integer',
                'year' => 'nullable|integer',
                'overview' => 'nullable|string',
                'poster_url' => 'nullable|url',
                'backdrop_url' => 'nullable|url',
                'runtime' => 'nullable|integer',
                'rating' => 'nullable|numeric',
                'genres' => 'nullable|array',
                'genres.*' => 'exists:genres,id',
            ]);

            $movie->update($validated);

            if (!empty($validated['genres'])) {
                $movie->genres()->sync($validated['genres']);
            } else {
                $movie->genres()->detach();
            }

            return redirect()->route('movies.edit', ['movie' => $movie->id])->with('success', 'Filme atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Falha ao atualizar o filme.');
        }
    }

    public function destroy(Movie $movie)
    {
        try {
            $movie->delete();
            return redirect()->route('movies.index')->with('success', 'Filme excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Falha ao excluir o filme.');
        }
    }

    public function linkManager(Movie $movie)
    {
        $links = $movie->playLinks;
        return view('dashboard.movies.linkmanager', ['movie' => $movie, 'links' => $links]);
    }
}
