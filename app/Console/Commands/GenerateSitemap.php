<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Movie;
use App\Models\Serie;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Gera o sitemap.xml do site com filmes e séries.';

    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/'))
            ->add(Url::create('/filmes'))
            ->add(Url::create('/series'));

        // Adiciona filmes individuais
        foreach (Movie::all() as $movie) {
            $sitemap->add(Url::create(route('movie.show', $movie->slug)));
        }

        // Adiciona séries individuais
        foreach (Serie::all() as $serie) {
            $sitemap->add(Url::create(route('serie.show', $serie->slug)));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap gerado com sucesso!');
    }
}
