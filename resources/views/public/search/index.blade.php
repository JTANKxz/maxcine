@extends('layouts.public')

@section('title', 'Pesquisa - ' . config('app.name'))

@section('content')
    <section class="sessao-todos-filmes">
        <div class="cabecalho-sessao">
            <h2 class="titulo-sessao">Resultados da busca por: "{{ $query }}"</h2>
        </div>

        @if($results->isEmpty())
            <p style="color: var(--cor-texto-claro); padding: 20px;">Nenhum resultado encontrado.</p>
        @else
            <div class="grid-filmes-container">
                @foreach ($results as $item)
                    @php
                        $isSerie = $item instanceof \App\Models\Serie;
                        $rating5 = $item->rating / 2;
                        $fullStars = floor($rating5);
                        $halfStar = ($rating5 - $fullStars) >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                        $route = $isSerie
                            ? route('serie.show', $item->slug)
                            : route('movie.show', $item->slug);
                    @endphp

                    <a href="{{ $route }}" class="filme" data-titulo-completo="{{ $item->title }}" data-ano="{{ $item->year }}"
                        data-avaliacao="{{ number_format($rating5, 1) }}">

                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}"
                            onerror="this.onerror=null;this.src='https://placehold.co/150x220/cccccc/000000?text=Poster';">

                        <div class="card-overlay">
                            <div class="overlay-titulo">{{ $item->title }}</div>
                            <div class="overlay-ano">{{ $item->year }}</div>

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
        @endif
    </section>
@endsection