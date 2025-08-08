<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\EpisodePlayLink;
use Illuminate\Http\Request;

class EpisodePlayLinkController extends Controller
{
    public function index(Episode $episode)
    {
        return view('dashboard.series.linkmanager', [
            'episode' => $episode,
            'links' => $episode->playLinks, // ou EpisodePlayLink::where('episode_id', $episode->id)->get()
        ]);
    }

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
            'quality' => 'required',
            'order' => 'required',
            'player_sub' => 'required',
        ]);

        EpisodePlayLink::create($validated);

        return redirect()->route('episodes.links.index', ['episode' => $validated['episode_id']])
            ->with('success', 'Link criado com sucesso!');
    }

    public function edit(EpisodePlayLink $link)
    {
        return view('dashboard.series.edit-link', ['link' => $link]);
    }

    public function update(Request $request, EpisodePlayLink $link)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'name' => 'required',
            'type' => 'required',
            'quality' => 'required',
            'order' => 'required',
            'player_sub' => 'required',
        ]);

        $link->update($validated);

        return redirect()->route('episodes.links.index', ['episode' => $link->episode_id])
            ->with('success', 'Link atualizado com sucesso!');
    }


    public function destroy(EpisodePlayLink $link)
    {
        $link->delete();
        return redirect()->back()->with('success', 'Link deletado com sucesso!');
    }
}
