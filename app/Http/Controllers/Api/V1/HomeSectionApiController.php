<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomHomeSection;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Http\Request;

class HomeSectionApiController extends Controller
{
    public function index()
    {
        $sections = CustomHomeSection::with(['items'])
            ->where('active', true)
            ->orderBy('order')
            ->get();

        $response = $sections->map(function ($section) {
            $items = $section->items->sortBy('order')->map(function ($item) {
                if ($item->content_type === 'movie') {
                    $content = Movie::find($item->content_id);
                } else {
                    $content = Serie::find($item->content_id);
                }

                if (!$content) return null;

                return [
                    'id' => $content->id,
                    'title' => $content->title ?? $content->name,
                    'slug' => $content->slug,
                    'year' => $content->year,
                    'rating' => $content->rating,
                    'backdrop_url' => $content->backdrop_url,
                    'type' => $item->content_type,
                ];
            })->filter(); // remove nulls

            return [
                'id' => $section->id,
                'name' => $section->name,
                'order' => $section->order,
                'items' => $items->values(),
            ];
        });

        return response()->json($response);
    }
}

