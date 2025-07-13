@extends('layouts.public')

@section('title', 'Todos os filmes - ' . config('app.name'))

@section('content')
    <section class="sessao-todos-filmes">
        <div class="cabecalho-sessao">
            <h2 class="titulo-sessao">Todos os Filmes</h2>
        </div>
        <div class="grid-filmes-container">
            @foreach ($movies as $movie)
                @php
                    $rating5 = $movie->rating / 2;
                    $fullStars = floor($rating5);
                    $halfStar = ($rating5 - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                <a href="{{ route('movie.show', ['slug' => $movie->slug]) }}" class="filme"
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
        <nav aria-label="Paginação de resultados">
            <div class="paginacao-scroll-wrapper">
                <ul class="paginacao-container">
                    {{-- Anterior --}}
                    <li class="paginacao-item">
                        @if ($movies->onFirstPage())
                            <span class="paginacao-link desabilitado" aria-disabled="true">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a class="paginacao-link" href="{{ $movies->previousPageUrl() }}" aria-label="Anterior">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                    </li>

                    {{-- Páginas numeradas --}}
                    @php
                        $start = max(1, $movies->currentPage() - 2);
                        $end = min($movies->lastPage(), $movies->currentPage() + 2);
                    @endphp

                    @if ($start > 1)
                        <li class="paginacao-item"><a class="paginacao-link" href="{{ $movies->url(1) }}">1</a></li>
                        @if ($start > 2)
                            <li class="paginacao-item"><span class="paginacao-link desabilitado">...</span></li>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="paginacao-item">
                            <a class="paginacao-link {{ $i == $movies->currentPage() ? 'ativo' : '' }}"
                                href="{{ $movies->url($i) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor

                    @if ($end < $movies->lastPage())
                        @if ($end < $movies->lastPage() - 1)
                            <li class="paginacao-item"><span class="paginacao-link desabilitado">...</span></li>
                        @endif
                        <li class="paginacao-item"><a class="paginacao-link"
                                href="{{ $movies->url($movies->lastPage()) }}">{{ $movies->lastPage() }}</a></li>
                    @endif

                    {{-- Próximo --}}
                    <li class="paginacao-item">
                        @if ($movies->hasMorePages())
                            <a class="paginacao-link" href="{{ $movies->nextPageUrl() }}" aria-label="Próximo">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="paginacao-link desabilitado" aria-disabled="true">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </li>
                </ul>
            </div>
        </nav>

    </section>
@endsection