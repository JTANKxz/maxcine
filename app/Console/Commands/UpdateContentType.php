<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use App\Models\Serie;

class UpdateContentType extends Command
{
    protected $signature = 'content:update-type';

    protected $description = 'Atualiza o campo content_type de filmes e séries';

    public function handle()
    {
        // Atualiza todos os filmes para content_type = 'movie'
        Movie::query()->update(['content_type' => 'movie']);
        $this->info('Filmes atualizados com content_type = movie');

        // Atualiza todas as séries para content_type = 'serie'
        Serie::query()->update(['content_type' => 'serie']);
        $this->info('Séries atualizadas com content_type = serie');

        $this->info('Atualização finalizada!');
        return 0;
    }
}
