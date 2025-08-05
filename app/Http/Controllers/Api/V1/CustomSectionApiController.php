<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CustomHomeSection;
use App\Models\Movie;
use App\Models\Serie;
use Illuminate\Http\Request;

class CustomSectionApiController extends Controller
{
    public function show($id)
    {
        $section = CustomHomeSection::with(['items'])
            ->where('active', true)
            ->where('id', $id)
            ->firstOrFail();

        $items = $section->items->sortBy('order')->map(function ($item) {
            if ($item->content_type === 'movie') {
                $content = Movie::find($item->content_id);
            } elseif ($item->content_type === 'serie') {
                $content = Serie::find($item->content_id);
            } else {
                $content = null;
            }

            if (!$content) return null;

            return [
                'id' => $content->id,
                'title' => $content->title ?? $content->name,
                'slug' => $content->slug,
                'year' => $content->year,
                'rating' => $content->rating,
                'poster_url' => $content->poster_url,
                'type' => $item->content_type,
            ];
        })->filter()->values(); // remove nulls e reseta índices

        return response()->json([
            'id' => $section->id,
            'name' => $section->name,
            'order' => $section->order,
            'items' => $items,
        ]);
    }
}
