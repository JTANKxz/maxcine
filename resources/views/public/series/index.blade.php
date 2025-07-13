@extends('layouts.public')

@section('title', 'Todas as séries- ' . config('app.name'))

@section('content')
    <section class="sessao-todos-filmes">
        <div class="cabecalho-sessao">
            <h2 class="titulo-sessao">Todos os Séries</h2>
        </div>
        <div class="grid-filmes-container">
            @foreach ($series as $serie)
                @php
                    $rating5 = $serie->rating / 2;
                    $fullStars = floor($rating5);
                    $halfStar = ($rating5 - $fullStars) >= 0.5;
                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                @endphp

                <a href="{{ route('serie.show', ['slug' => $serie->slug]) }}" class="filme"
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
        <nav aria-label="Paginação de resultados">
            <div class="paginacao-scroll-wrapper">
                <ul class="paginacao-container">
                    {{-- Anterior --}}
                    <li class="paginacao-item">
                        @if ($series->onFirstPage())
                            <span class="paginacao-link desabilitado" aria-disabled="true">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a class="paginacao-link" href="{{ $series->previousPageUrl() }}" aria-label="Anterior">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                    </li>

                    {{-- Páginas numeradas --}}
                    @php
                        $start = max(1, $series->currentPage() - 2);
                        $end = min($series->lastPage(), $series->currentPage() + 2);
                    @endphp

                    @if ($start > 1)
                        <li class="paginacao-item"><a class="paginacao-link" href="{{ $series->url(1) }}">1</a></li>
                        @if ($start > 2)
                            <li class="paginacao-item"><span class="paginacao-link desabilitado">...</span></li>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        <li class="paginacao-item">
                            <a class="paginacao-link {{ $i == $series->currentPage() ? 'ativo' : '' }}"
                                href="{{ $series->url($i) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor

                    @if ($end < $series->lastPage())
                        @if ($end < $series->lastPage() - 1)
                            <li class="paginacao-item"><span class="paginacao-link desabilitado">...</span></li>
                        @endif
                        <li class="paginacao-item"><a class="paginacao-link"
                                href="{{ $series->url($series->lastPage()) }}">{{ $series->lastPage() }}</a></li>
                    @endif

                    {{-- Próximo --}}
                    <li class="paginacao-item">
                        @if ($series->hasMorePages())
                            <a class="paginacao-link" href="{{ $series->nextPageUrl() }}" aria-label="Próximo">
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