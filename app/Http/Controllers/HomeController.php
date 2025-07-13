<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\Slider;


class HomeController extends Controller
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


        return view('home', compact('genres', 'movies', 'series', 'sliders'));
    }
}
