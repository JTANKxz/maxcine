<?php

namespace App\Http\Controllers;

use App\Models\TVChannel;
use App\Models\TVChannelLink;
use Illuminate\Http\Request;

class TVChannelController extends Controller
{
    public function index()
    {
        $channels = TVChannel::with('links')->orderBy('name')->get();
        return view('public.tv.index', compact('channels'));
    }

    public function show($slug)
    {
        $channel = TVChannel::where('slug', $slug)->with('links')->firstOrFail();
        return view('public.tv.show', compact('channel'));
    }

    public function listLinks(TVChannel $channel)
    {
        $links = $channel->links()->orderBy('order')->get();
        return view('dashboard.tv.list-link', ['channel' => $channel, 'links' => $links]);
    }

    public function createLink(TVChannel $channel)
    {
        return view('dashboard.tv.create-link', ['channel' => $channel]);
    }

    public function storeLink(Request $request, TVChannel $channel)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'type' => 'required|string',
            'player_sub' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        $channel->links()->create($data);
        return redirect()->route('channels.links.index', $channel)->with('success', 'Link created successfully.');
    }

    public function editLink(TVChannel $channel)
    {
        return view('dashboard.tv.edit-link', ['channel' => $channel]);
    }

    public function updateLink(Request $request, TVChannel $channel)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'url' => 'required|url',
                'type' => 'required|string',
                'player_sub' => 'nullable|string',
                'order' => 'required|integer',
            ]);

            $channel->links()->update($data);
            return redirect()->route('channels.links.index', $channel)->with('success', 'Link updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar');
        }
    }

    public function destroyLink(TVChannel $channel, $linkId)
    {
        $link = $channel->links()->where('id', $linkId)->firstOrFail();
        $link->delete();

        return redirect()->route('channels.links.index', $channel)
            ->with('success', 'Link deleted successfully.');
    }

    public function destroyChannel(TVChannel $channel)
    {
        $channel->delete();
        return redirect()->route('channels.index')->with('success', 'Channel deleted successfully.');
    }
}
