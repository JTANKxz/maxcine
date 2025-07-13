<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Serie;
use Illuminate\Support\Facades\Http;

class AtualizarImdbSeries extends Command
{
    protected $signature = 'series:atualizar-imdb';
    protected $description = 'Atualiza o imdb_id de todas as séries salvas no banco usando o TMDB';

    protected $apiKey = 'edcd52275afd8b8c152c82f1ce3933a2'; // substitua pela sua

    public function handle()
    {
        $series = Serie::whereNotNull('tmdb_id')->get();
        $this->info("Iniciando atualização de IMDb IDs para {$series->count()} séries...");

        foreach ($series as $serie) {
            $response = Http::get("https://api.themoviedb.org/3/tv/{$serie->tmdb_id}/external_ids", [
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $externalData = $response->json();
                $imdbId = $externalData['imdb_id'] ?? null;

                if ($imdbId && $serie->imdb_id !== $imdbId) {
                    $serie->imdb_id = $imdbId;
                    $serie->save();
                    $this->info("✅ Série '{$serie->title}' atualizada com IMDb ID: {$imdbId}");
                } else {
                    $this->line("➖ Série '{$serie->title}' já possui o IMDb ID ou não foi encontrado.");
                }
            } else {
                $this->error("❌ Falha ao buscar dados para '{$serie->title}' (TMDB ID: {$serie->tmdb_id})");
            }

            // Respeita limites de requisição
            usleep(300000); // 0.3 segundos
        }

        $this->info('✔️ Atualização concluída.');
    }
}
