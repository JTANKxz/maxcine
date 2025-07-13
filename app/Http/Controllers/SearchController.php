<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 3) {
            return view('public.search.index', [
                'query' => $query,
                'results' => collect(), // vazio
            ]);
        }

        $movies = Movie::where('title', 'like', "%$query%")->get();
        $series = Serie::where('title', 'like', "%$query%")->get();

        $results = $movies->concat($series)->sortByDesc('year');

        return view('public.search.index', compact('query', 'results'));
    }
}
