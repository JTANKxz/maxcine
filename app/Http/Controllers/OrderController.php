<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    // Tela inicial de pesquisa de pedidos
    public function searchForm()
    {
        return view('public.orders.search');
    }

    // Busca em tempo real (usado via AJAX)
    public function liveSearch(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');

        if (!$query || !$type || !in_array($type, ['movie', 'tv'])) {
            return response()->json([]);
        }

        $response = Http::get("https://api.themoviedb.org/3/search/{$type}", [
            'api_key' => 'edcd52275afd8b8c152c82f1ce3933a2',
            'language' => 'pt-BR',
            'query' => $query
        ]);

        $results = $response->json()['results'] ?? [];

        return response()->json($results);
    }

    // Confirmação do pedido
    public function create(Request $request)
    {
        $type = $request->input('type');
        $tmdbId = $request->input('tmdb_id');

        if (!$type || !$tmdbId || !in_array($type, ['movie', 'tv'])) {
            abort(404);
        }

        $response = Http::get("https://api.themoviedb.org/3/{$type}/{$tmdbId}", [
            'api_key' => 'edcd52275afd8b8c152c82f1ce3933a2',
            'language' => 'pt-BR'
        ]);

        $data = $response->json();

        return view('orders.confirm', [
            'data' => $data,
            'type' => $type
        ]);
    }

    // Armazena o pedido no banco
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:movie,tv',
            'tmdb_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'poster_url' => 'nullable|url',
            'year' => 'nullable|string|max:4',
        ]);

        $order = Auth::user()->orders()->create([
            'type' => $request->type,
            'tmdb_id' => $request->tmdb_id,
            'title' => $request->title,
            'poster_url' => $request->poster_url,
            'year' => $request->year,
            'status' => 'pending',
            'total' => 0,
            'details' => null
        ]);

        return redirect()->route('orders.show', $order->id)->with('success', 'Pedido realizado com sucesso!');
    }

    // Lista os pedidos do usuário logado
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // Exibe um pedido específico
    public function show($id)
    {
        $order = Auth::user()->orders()->findOrFail($id);
        return view('public.orders.show', compact('order'));
    }
}
