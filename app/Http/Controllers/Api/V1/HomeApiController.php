<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeApiController extends Controller
{
    public function index()
    {
        $genres = Genre::all();

        $series = Serie::orderBy('year', 'desc')->take(15)->get();

        $movies = Movie::orderBy('year', 'desc')->take(15)->get();

        $sliders = Slider::with([
            'serie' => function ($query) {
                $query->withCount('seasons');
            }
        ])->orderBy('id', 'desc')->get();

        return response()->json([
            'genres' => $genres,
            'series' => $series,
            'movies' => $movies,
            'sliders' => $sliders,
        ]);
    }
}
