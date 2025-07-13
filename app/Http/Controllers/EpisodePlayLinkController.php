<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\EpisodePlayLink;
use Illuminate\Http\Request;

class EpisodePlayLinkController extends Controller
{
    public function create(Episode $episode)
    {
        return view('dashboard.series.create-link', ['episode' => $episode]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'episode_id' => 'required|exists:episodes,id',
            'url' => 'required|url',
            'name' => 'required',
            'type' => 'required',
        ]);

        EpisodePlayLink::create($validated);

        return redirect()->route('episodes.links.create', ['episode' => $validated['episode_id']])
            ->with('success', 'Link criado com sucesso!');
    }
}
