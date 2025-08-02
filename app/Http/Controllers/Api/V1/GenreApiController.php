<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class GenreApiController extends Controller
{

    public function show($id)
    {
        $genre = Genre::find($id);
        $movies = $genre->movies()->select('*')->addSelect(DB::raw("'movie' as type"))->get();
        $series = $genre->series()->select('*')->addSelect(DB::raw("'serie' as type"))->get();
        $conteudos = $movies->concat($series)->sortByDesc('year')->values();

        return response()->json([
            'genre' => $genre,
            'conteudos' => $conteudos,
        ]);
    }
}
