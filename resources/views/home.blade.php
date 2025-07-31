@extends('layouts.public')

@section('title', 'Início - ' . config('app.name'))

@php
    $meta_title = 'Início - ' . config('app.name');
    $meta_description = 'Assista aos melhores filmes e séries gratuitamente. Atualizado diariamente com os últimos lançamentos.';
    $meta_keywords = 'assistir filmes dublado, assistir filmes online grátis, assistir, séries online grátis, filmes online grátis, séries online grátis, assistir online, assistir online grátis, lançamento, hd, 2025';
    $meta_image = $sliders->first()->backdrop_url ?? asset('logo.jpg');
@endphp

@section('content')
    <section class="sessao-slides">
        {{-- <div class="cabecalho-sessao-home">
            <h2 class="titulo-sessao-home">Em Destaque</h2>
        </div> --}}
        <div class="slides-container">
            @foreach ($sliders as $slider)
                @php
                    $route = $slider->type === 'movie'
                        ? route('movie.show', $slider->slug)
                        : route('serie.show', $slider->slug);

                    // Texto do tipo (Filme ou Série)
                    $tipoTexto = $slider->type === 'movie' ? 'Filme' : 'Série';
                @endphp

                <a href="{{ $route }}" class="slide {{ $loop->first ? 'ativo' : '' }}">
                    <img src="{{ $slider->backdrop_url }}" alt="{{ $slider->title }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/600x338/cccccc/000000?text=Imagem+Indisponível';">
                    <div class="slide-info">
                        <h3>{{ $slider->title }}</h3>
                        <p>
                            {{ $slider->year }}

                            @if($slider->type === 'movie' && !empty($slider->runtime))
                                | {{ $slider->runtime }} min
                            @elseif($slider->type === 'serie' && $slider->serie?->seasons_count)
                                | {{ $slider->serie->seasons_count }}
                                temporada{{ $slider->serie->seasons_count > 1 ? 's' : '' }}
                            @endif
                        </p>
                        <p class="tipo-conteudo">{{ $tipoTexto }}</p>
                    </div>
                </a>
            @endforeach

            <div class="barra-progresso"></div>
        </div>
        <div class="indicadores"></div>
    </section>


    <section class="sessao-categorias">
        <div class="cabecalho-sessao-home">
            <h2 class="titulo-sessao-home">Gêneros</h2>
            {{-- <a href="#" class="btn-ver-tudo">Ver Tudo</a> --}}
        </div>
        <div class="categorias-container">
            @if(isset($genres) && count($genres) > 0)
                @foreach ($genres as $genre)
                    <a href="{{ route('genres.show', ['slug' => $genre->slug]) }}" class="categoria-btn">
                        {{ $genre->name }}
                    </a>
                @endforeach
            @else
                <p>Nenhum Gênero Disponível</p>
            @endif
        </div>
    </section>

    <section class="sessao-filmes">
        <div class="cabecalho-sessao-home">
            <h2 class="titulo-sessao-home">Filmes</h2>
            <a href="{{ route('movies') }}" class="btn-ver-tudo">Ver Tudo</a>
        </div>
        <div class="filmes-container-home">
            @foreach ($movies as $movie)
                @php
                    $rating5 = $movie->rating / 2; // converte de 10 para 5 estrelas
                    $fullStars = floor($rating5);
                    $halfStar = ($rating5 - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                <a href="{{ route('movie.show', ['slug' => $movie->slug]) }}" class="filme-home"
                    data-titulo-completo="{{ $movie->title }}" data-ano="{{ $movie->year }}"
                    data-avaliacao="{{ number_format($rating5, 1) }}">

                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/150x220/cccccc/000000?text=Poster';">

                    <div class="card-overlay">
                        <div class="overlay-titulo">{{ $movie->title }}</div>
                        <div class="overlay-ano">{{ $movie->year }}</div>

                        <div class="overlay-avaliacao-estrelas">
                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor

                            @if ($halfStar)
                                <i class="fa-solid fa-star-half-alt"></i>
                            @endif

                            @for ($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star"></i>
                            @endfor

                            <span class="rating-number">({{ number_format($rating5, 1) }})</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    <section class="sessao-series">
        <div class="cabecalho-sessao-home">
            <h2 class="titulo-sessao-home">Séries</h2>
            <a href="{{ route('series') }}" class="btn-ver-tudo">Ver Tudo</a>
        </div>
        <div class="series-container-home">
            @foreach ($series as $serie)
                @php
                    $rating5 = $serie->rating / 2;
                    $fullStars = floor($rating5);
                    $halfStar = ($rating5 - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                <a href="{{ route('serie.show', ['slug' => $serie->slug]) }}" class="serie-home"
                    data-titulo-completo="{{ $serie->title }}" data-ano="{{ $serie->year }}"
                    data-avaliacao="{{ number_format($rating5, 1) }}">

                    <img src="{{ $serie->poster_url }}" alt="{{ $serie->title }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/150x220/cccccc/000000?text=Poster';">

                    <div class="card-overlay">
                        <div class="overlay-titulo">{{ $serie->title }}</div>
                        <div class="overlay-ano">{{ $serie->year }}</div>

                        <div class="overlay-avaliacao-estrelas">
                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor

                            @if ($halfStar)
                                <i class="fa-solid fa-star-half-alt"></i>
                            @endif

                            @for ($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star"></i>
                            @endfor

                            <span class="rating-number">({{ number_format($rating5, 1) }})</span>
                        </div>
                    </div>
                </a>
            @endforeach

        </div>
    </section>

    <section class="sessao-series">
        <div class="cabecalho-sessao-home">
            <h2 class="titulo-sessao-home">Séries</h2>
            <a href="{{ route('series') }}" class="btn-ver-tudo">Ver Tudo</a>
        </div>
        <div class="series-container-home">
            @foreach ($series as $serie)
                @php
                    $rating5 = $serie->rating / 2;
                    $fullStars = floor($rating5);
                    $halfStar = ($rating5 - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                <a href="{{ route('serie.show', ['slug' => $serie->slug]) }}" class="serie-home"
                    data-titulo-completo="{{ $serie->title }}" data-ano="{{ $serie->year }}"
                    data-avaliacao="{{ number_format($rating5, 1) }}">

                    <img src="{{ $serie->poster_url }}" alt="{{ $serie->title }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/150x220/cccccc/000000?text=Poster';">

                    <div class="card-overlay">
                        <div class="overlay-titulo">{{ $serie->title }}</div>
                        <div class="overlay-ano">{{ $serie->year }}</div>

                        <div class="overlay-avaliacao-estrelas">
                            @for ($i = 0; $i < $fullStars; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor

                            @if ($halfStar)
                                <i class="fa-solid fa-star-half-alt"></i>
                            @endif

                            @for ($i = 0; $i < $emptyStars; $i++)
                                <i class="far fa-star"></i>
                            @endfor

                            <span class="rating-number">({{ number_format($rating5, 1) }})</span>
                        </div>
                    </div>
                </a>
            @endforeach

        </div>
    </section>
@endsection