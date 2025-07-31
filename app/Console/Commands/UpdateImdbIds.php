<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use Illuminate\Support\Facades\Http;

class UpdateImdbIds extends Command
{
    protected $signature = 'movies:update-imdb';
    protected $description = 'Atualiza o campo imdb_id dos filmes já importados';

    protected $apiKey = 'apikeyhere'; // ou pegue do env

    public function handle()
    {
        $movies = Movie::whereNull('imdb_id')->get();

        if ($movies->isEmpty()) {
            $this->info('Nenhum filme precisa ser atualizado.');
            return;
        }

        foreach ($movies as $movie) {
            $this->line("Atualizando filme: {$movie->title} (TMDB ID: {$movie->tmdb_id})");

            $response = Http::get("https://api.themoviedb.org/3/movie/{$movie->tmdb_id}", [
                'api_key' => $this->apiKey,
                'language' => 'pt-BR',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $movie->imdb_id = $data['imdb_id'] ?? null;
                $movie->save();
                $this->info(" → Atualizado com IMDb ID: {$movie->imdb_id}");
            } else {
                $this->error(" → Falha ao buscar dados para TMDB ID: {$movie->tmdb_id}");
            }
        }

        $this->info('Atualização concluída.');
    }
}
