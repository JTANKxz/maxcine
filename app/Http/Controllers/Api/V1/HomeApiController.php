<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Models\CustomHomeSection;

class HomeApiController extends Controller
{
    public function index()
    {
        $genres = Genre::all();
        $series = Serie::orderBy('id', 'desc')->take(15)->get();
        $movies = Movie::orderBy('id', 'desc')->take(15)->get();
        $sliders = Slider::with(['serie' => fn($q) => $q->withCount('seasons')])->orderBy('id', 'desc')->get();

        $sections = CustomHomeSection::with(['items'])->where('active', true)->orderBy('order')->get();

        $formattedSections = $sections->map(function ($section) {
            $items = $section->items->sortBy('order')->map(function ($item) {
                $model = $item->content_type === 'movie' ? Movie::find($item->content_id) : Serie::find($item->content_id);

                if (!$model) return null;

                return [
                    'id' => $model->id,
                    'title' => $model->title ?? $model->name,
                    'slug' => $model->slug,
                    'year' => $model->year,
                    'rating' => $model->rating,
                    'poster_url' => $model->poster_url,
                    'type' => $item->content_type,
                ];
            })->filter();

            return [
                'id' => $section->id,
                'name' => $section->name,
                'order' => $section->order,
                'items' => $items->values(),
            ];
        });

        return response()->json([
            'genres' => $genres,
            'series' => $series,
            'movies' => $movies,
            'sliders' => $sliders,
            'sections' => $formattedSections, // ✅ Aqui sim, no formato esperado pelo app
        ]);
    }
}
