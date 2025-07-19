@extends('layouts.public')

@section('title', "Assistir Filme {$movie->title} - online" . config('app.name'))

@php
    $meta_title = "Assistir {$movie->title} - online grátis" . config('app.name');
    $meta_description = Str::limit($movie->overview, 150) ?: 'Veja os detalhes do filme e assista agora online.';
    $meta_keywords = implode(', ', $movie->genres->pluck('name')->toArray()) . ', assistir, filme, online, ' . $movie->title;
    $meta_image = $movie->poster_url ?? asset('logo.jpg');
@endphp

@section('content')

    @section('structured-data')
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Movie",
                "name": "{{ $movie->title }}",
                "image": "{{ $movie->poster_url }}",
                "description": "{{ Str::limit($movie->overview, 200) }}",
                "datePublished": "{{ $movie->year }}",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "{{ number_format($movie->rating / 2, 1) }}",
                    "bestRating": "5",
                    "ratingCount": 1000
                },
                "genre": {!! json_encode($movie->genres->pluck('name')->toArray()) !!}
            }
            </script>
    @endsection

    <style>
        .info-filme-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        /* Estilo do botão coração */
        .heart-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            border: none;
            background: none;
            cursor: pointer;
            color: var(--cor-primaria);
            /* cor padrão do site */
            transition: color 0.3s;
        }

        .heart-button .heart-icon {
            font-size: 28px;
        }

        .heart-button .heart-label {
            font-size: 14px;
            margin-top: 4px;
        }

        /* Quando estiver salvo */
        .heart-button.salvo {
            color: var(--cor-primaria);
            /* Verde */
        }

        .heart-button.salvo .heart-icon {
            color: var(--cor-primaria);
        }

        .heart-button:hover:not(.salvo) .heart-icon {
            color: #666;
        }
    </style>
    <div id="modalPlayer" class="modal-player">
        <div class="modal-player-conteudo">
            <span class="btn-fechar-modal" onclick="fecharModalPlayer()">×</span>
            <h3>Escolha uma Opção</h3>
            <div class="opcoes-player">
                @foreach ($links as $link)
                    @php
                        $type = $link->type ?? 'embed';
                        $url = $link->url ?? '';
                    @endphp
                    <button onclick="iniciarPlayer(this)" data-url="{{ $url }}" data-type="{{ $type }}">
                        {{ $link->name ?? 'Player' }} - {{ $link->quality ?? '' }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <section class="player-filme-secao">
        <div class="player-container-wrapper">
            <div class="player-area" id="playerAreaContainer">
                <img src="{{ $movie->backdrop_url ?? 'https://placehold.co/1280x720/1a1919/ffc107?text=Filme+em+Destaque' }}"
                    alt="{{ $movie->title ?? 'Filme em Destaque' }}" class="player-imagem-fundo" id="playerImagemFundo"
                    onerror="this.onerror=null;this.src='https://placehold.co/1280x720/cccccc/000000?text=Imagem+Indisponível';">
                <i class="fas fa-play btn-play-central" id="btnPlayCentral" onclick="abrirModalPlayer()"></i>
            </div>
            <button id="btnMudarPlayer" class="btn-mudar-player hidden" onclick="abrirModalPlayer()">
                <i class="fas fa-sync-alt"></i> Trocar Player
            </button>


        </div>

        <div class="info-filme-container">
            <div class="info-filme-header">
                <h1 class="info-filme-titulo">{{ $movie->title ?? 'Sem Título' }}</h1>

                @auth
                    @php
                        $inWatchlist = auth()->user()
                            ->{"watchlist" . ucfirst('movie') . "s"}()
                                ->where('movies.id', $movie->id)
                                ->exists();
                    @endphp

                    <form method="POST" action="{{ route('watchlist.toggle') }}" class="watchlist-form"
                        data-id="{{ $movie->id }}" data-type="movie">
                        @csrf
                        <input type="hidden" name="id" value="{{ $movie->id }}">
                        <input type="hidden" name="type" value="movie">
                        <button type="submit" class="heart-button {{ $inWatchlist ? 'salvo' : '' }}">
                            <i class="fa{{ $inWatchlist ? 's' : 'r' }} fa-heart heart-icon"></i>
                            <span class="heart-label">{{ $inWatchlist ? 'Salvo' : 'Salvar' }}</span>
                        </button>
                    </form>
                @endauth
            </div>



            <div class="info-filme-meta">
                <span>{{ $movie->year ?? 'Sem data' }}</span>
                <span>•</span>
                <span>{{ $movie->runtime ?? 'Indisponível' }} min</span>
                <span>•</span>
                {{-- SUGESTÃO: Usar um componente Blade para as estrelas --}}
                {{-- Ex: <x-star-rating :ratingValue="$movie->rating" /> --}}
                <span class="estrelas-avaliacao" id="estrelasAvaliacaoFilme">
                    @php
                        $rating5 = ($movie->rating ?? 0) / 2;
                        $fullStars = floor($rating5);
                        $halfStar = ($rating5 - $fullStars) >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    @endphp
                    @for ($i = 0; $i < $fullStars; $i++)
                        <i class="fa-solid fa-star"></i>
                    @endfor
                    @if ($halfStar)
                        <i class="fa-solid fa-star-half-alt"></i>
                    @endif
                    @for ($i = 0; $i < $emptyStars; $i++)
                        <i class="far fa-star"></i>
                    @endfor
                </span>
                <span class="rating-number">({{ number_format($rating5, 1) }})</span>
            </div>
            <p class="info-filme-sinopse">
                {{ $movie->overview ?? 'Sem sinopse.' }}
            </p>
        </div>
    </section>

    <section class="sessao-generos-filme">
        <div class="cabecalho-sessao">
            <h2 class="titulo-sessao">Gêneros</h2>
        </div>
        <div class="generos-container-filme">
            @forelse ($genres as $genre)
                <a href="{{ route('genres.show', ['slug' => $genre->slug]) }}" class="genero-btn-filme">
                    {{ $genre->name }}
                </a>
            @empty
                <span class="genero-btn-filme">Sem gêneros disponíveis</span>
            @endforelse
        </div>
    </section>

    <section class="sessao-relacionados">
        <div class="cabecalho-sessao">
            <h2 class="titulo-sessao">Relacionados</h2>
        </div>
        <div class="filmes-container">
            @forelse ($relatedMovies as $related)
                @php
                    // SUGESTÃO: A lógica de estrelas também pode usar o componente Blade aqui
                    $rating5_related = ($related->rating ?? 0) / 2;
                    $fullStars_related = floor($rating5_related);
                    $halfStar_related = ($rating5_related - $fullStars_related) >= 0.5;
                    $emptyStars_related = 5 - $fullStars_related - ($halfStar_related ? 1 : 0);
                @endphp
                <a href="{{ route('movie.show', ['slug' => $related->slug]) }}" class="filme"
                    data-titulo-completo="{{ $related->title }}" data-ano="{{ $related->year }}"
                    data-avaliacao="{{ number_format($rating5_related, 1) }}">
                    <img src="{{ $related->poster_url ?? 'https://placehold.co/150x220/cccccc/000000?text=Poster' }}"
                        alt="{{ $related->title }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/150x220/cccccc/000000?text=Poster';">
                    <div class="card-overlay">
                        <div class="overlay-titulo">{{ Str::limit($related->title, 25) }}</div>
                        <div class="overlay-ano">{{ $related->year }}</div>
                        <div class="overlay-avaliacao-estrelas">
                            @for ($i = 0; $i < $fullStars_related; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            @if ($halfStar_related)
                                <i class="fa-solid fa-star-half-alt"></i>
                            @endif
                            @for ($i = 0; $i < $emptyStars_related; $i++)
                                <i class="far fa-star"></i>
                            @endfor
                            <span class="rating-number">({{ number_format($rating5_related, 1) }})</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="filme" style="text-align: center; width: 100%;">
                    <p style="color: var(--cor-texto-claro);">Nenhum filme relacionado disponível.</p>
                </div>
            @endforelse
        </div>

    </section>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.watchlist-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const id = this.dataset.id;
                    const type = this.dataset.type;
                    const button = this.querySelector('.heart-button');

                    const icon = button.querySelector('i');
                    const label = button.querySelector('.heart-label');

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': this.querySelector('[name="_token"]').value,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({
                            id: id,
                            type: type
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.added) {
                                button.classList.add('salvo');
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                                label.textContent = 'Salvo';
                            } else {
                                button.classList.remove('salvo');
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                                label.textContent = '';
                            }

                            showToast(data.message);
                        });
                });
            });

            // Toast
            function showToast(message) {
                const toast = document.getElementById('toast');
                toast.textContent = message;
                toast.classList.remove('hidden');
                toast.classList.add('show-toast');

                setTimeout(() => {
                    toast.classList.remove('show-toast');
                    toast.classList.add('hidden');
                }, 3000);
            }
        });
    </script>

    <style>
        #toast.show-toast {
            display: block !important;
            animation: fadeInOut 3s ease-in-out;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            10% {
                opacity: 1;
                transform: translateY(0);
            }

            90% {
                opacity: 1;
                transform: translateY(0);
            }

            100% {
                opacity: 0;
                transform: translateY(20px);
            }
        }
    </style>




@endsection