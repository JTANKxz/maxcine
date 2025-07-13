<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class GenreController extends Controller
{
    public function index() {}

    public function show($slug)
    {
        $genre = Genre::where('slug', $slug)->firstOrFail();

        $movies = $genre->movies()->select('*')->addSelect(DB::raw("'movie' as type"))->get();
        $series = $genre->series()->select('*')->addSelect(DB::raw("'serie' as type"))->get();

        $conteudos = $movies->concat($series)->sortByDesc('year')->values();

        $perPage = 24;
        Paginator::currentPageResolver(fn() => request()->input('page', 1));
        $currentPage = request()->input('page', 1);

        $currentItems = $conteudos->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $currentItems,
            $conteudos->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => ['page' => $currentPage]]
        );

        return view('public.genres.show', compact('genre', 'paginated'));
    }
}
