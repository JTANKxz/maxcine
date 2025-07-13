<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Movie;
use App\Models\Serie;
use App\Models\User;
use Illuminate\Container\Attributes\DB;

class WatchlistController extends Controller
{

    public function toggleWatchlist(Request $request)
    {
        // 1. Validação: Garante que os dados corretos foram enviados. Isso é uma ótima prática.
        $validated = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:movie,serie', // Garante que 'type' seja 'movie' ou 'serie'
        ]);

        $user = Auth::user();
        $id = $validated['id'];
        $type = $validated['type'];

        $wasAdded = false;

        // 2. Lógica simplificada com toggle()
        // Operar a partir do usuário ($user->...) é geralmente mais intuitivo para watchlists.
        if ($type === 'movie') {
            // O método toggle faz tudo para você.
            // Ele retorna um array com os IDs que foram anexados e desanexados.
            $syncResult = $user->watchlistMovies()->toggle([$id]);

            // Se o array 'attached' não estiver vazio, significa que o item foi ADICIONADO.
            $wasAdded = !empty($syncResult['attached']);
        } elseif ($type === 'serie') {
            // A mesma lógica para séries
            $syncResult = $user->watchlistSeries()->toggle([$id]);
            $wasAdded = !empty($syncResult['attached']);
        }

        $message = $wasAdded ? 'Adicionado à sua lista!' : 'Removido da sua lista!';

        // 3. Retorno para AJAX (sem mudanças aqui, já estava bom)
        if ($request->ajax()) {
            return response()->json([
                'message' => $message,
                'added' => $wasAdded,
            ]);
        }

        return back()->with('success', $message);
    }




    public function index()
    {
        $user = Auth::user();
        $items = $user->watchlistItems();

        return view('watchlist.index', compact('items'));
    }
}
