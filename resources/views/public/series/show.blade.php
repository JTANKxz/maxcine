@extends('layouts.public')

@section('title', "Assistir {$serie->title} - " . config('app.name'))

@php
    $meta_title = "Assistir {$serie->title} online grátis - " . config('app.name');
    $meta_description = Str::limit($serie->overview, 150) ?: 'Veja os detalhes da série e assista agora online.';
    $meta_keywords = implode(', ', $serie->genres->pluck('name')->toArray()) . ', assistir, série, online, ' . $serie->title;
    $meta_image = $serie->poster_url ?? asset('logo.jpg');
@endphp

@section('content')

    @section('structured-data')
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "TVSeries",
                "name": "{{ $serie->title }}",
                "image": "{{ $serie->poster_url }}",
                "description": "{{ Str::limit($serie->overview, 200) }}",
                "datePublished": "{{ $serie->year }}",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "{{ number_format($serie->rating / 2, 1) }}",
                    "bestRating": "5",
                    "ratingCount": 1000
                },
                "genre": {!! json_encode($serie->genres->pluck('name')->toArray()) !!}
            }
        </script>
    @endsection

    <style>
        .heart-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: var(--cor-primaria);
            transition: color 0.3s;
            position: absolute;
            top: 0;
            right: 0;
            margin-top: 8px;
        }

        .heart-button i {
            font-size: 26px;
            transition: color 0.3s;
        }

        .heart-button .heart-label {
            font-size: 12px;
            color: var(--cor-primaria);
        }

        .heart-button.salvo i {
            color: limegreen;
        }

        .heart-button.salvo .heart-label {
            color: limegreen;
        }

        /* Toast visível */
        .show-toast {
            animation: fadeInOut 3s ease-in-out forwards;
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

    <div id="modalPlayer" class="modal-player">
        <div class="modal-player-conteudo">
            <span class="btn-fechar-modal" onclick="fecharModalPlayer()">×</span>
            <h3 id="modalPlayerTitle">Escolha uma Opção</h3>
            <div class="opcoes-player" id="opcoesPlayerModal">
                <p id="noPlayerOptionsMsg" class="hidden" style="color: var(--cor-texto-claro);">Nenhuma opção de player
                    disponível para este episódio.</p>
            </div>
        </div>
    </div>

    <section class="player-destaque-area" id="playerDestaqueArea">
        <img src="{{ $serie->backdrop_url ?? 'https://placehold.co/1920x1080/111/ffc107?text=Destaque+da+Série' }}"
            alt="{{ $serie->title ?? 'Sem Título' }}" class="serie-backdrop-inicial" id="serieBackdropInicial"
            onerror="this.onerror=null;this.src='https://placehold.co/1280x720/cccccc/000000?text=Imagem+Indisponível';">

    </section>

    <div class="controles-navegacao-episodio hidden" id="controlesNavegacaoEpisodio">
        <button id="btnEpAnterior" onclick="navegarEpisodio('anterior')"><i class="fas fa-chevron-left"></i>
            Anterior</button>
        <button id="btnMudarPlayerEp" class="btn-mudar-player-ep" onclick="reabrirModalPlayerAtual()">
            <i class="fas fa-sync-alt"></i> Opções
        </button>

        <button id="btnEpProximo" onclick="navegarEpisodio('proximo')">Próximo <i class="fas fa-chevron-right"></i></button>
    </div>



    <section class="info-serie-fixa-container">
        <div class="info-serie-header" style="position: relative;">
            <h1 class="info-serie-fixa-titulo">{{ $serie->title ?? 'Nome Incrível da Série' }}</h1>
            <!-- form com botão coração -->
            @auth
                @php
                    $inWatchlist = auth()->user()
                        ->watchlistSeries()
                        ->where('series.id', $serie->id)
                        ->exists();
                @endphp

                <form method="POST" action="{{ route('watchlist.toggle') }}" class="watchlist-form heart-form"
                    data-id="{{ $serie->id }}" data-type="serie">
                    @csrf
                    <button type="submit" class="heart-button {{ $inWatchlist ? 'salvo' : '' }}">
                        <i class="{{ $inWatchlist ? 'fas' : 'far' }} fa-heart"></i>
                        <span class="heart-label">{{ $inWatchlist ? 'Salvo' : 'Salvar' }}</span>
                    </button>
                </form>

            @endauth
        </div>


        <div class="info-serie-fixa-meta">
            <span>{{ $serie->year ?? 'Sem data' }}</span>
            <span>•</span>
            <span id="numeroTemporadasFixo">{{ $serie->seasons_count ?? 3 }} Temporadas</span>
            <span>•</span>
            <span class="estrelas-avaliacao" id="estrelasAvaliacaoSerieFixo">
                @php
                    $rating5 = $serie->rating / 2; // converte de 10 para 5 estrelas
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
        </div>
        <p class="info-serie-fixa-sinopse">
            {{ $serie->overview ?? 'Sem sinopse.' }}
        </p>
    </section>


    <section class="temporadas-episodios-container">
        <div class="cabecalho-sessao">
            <h2 class="titulo-sessao">Temporadas e Episódios</h2>
        </div>

        <div class="botoes-temporadas" id="botoesTemporadasContainer">
            {{-- Botões das temporadas --}}
            @if(isset($seasons) && $seasons->count())
                @foreach($seasons as $season)
                    <button class="btn-temporada @if($loop->first) ativo @endif"
                        data-temporada-target="temporada-{{ $season->season_number }}-episodios">
                        Temporada {{ $season->season_number }}
                    </button>

                @endforeach
            @else
                <p>Nenhuma temporada</p>
            @endif
        </div>

        <div id="listaEpisodiosWrapper">
            {{-- Listas de episódios por temporada --}}
            @if(isset($seasons) && $seasons->count())
                @foreach($seasons as $season)
                    <div class="episodios-scroll">
                        <ul class="lista-episodios @if(!$loop->first) hidden @endif"
                            id="temporada-{{ $season->season_number }}-episodios">
                            @if($season->episodes->count())
                                @foreach($season->episodes as $ep)
                                    <li data-ep-numero="{{ $ep->episode_number }}" data-ep-titulo="{{ $ep->name }}"
                                        data-links="{{ json_encode($ep->playLinks ?? []) }}">
                                        <div class="image-ep">
                                            <img src="{{ $ep->still_url }}" alt="">
                                        </div>
                                        <div class="ep-numero-titulo">
                                            <span class="ep-numero">EP {{ $ep->episode_number }}</span>
                                            <span class="ep-titulo">{{ $ep->name }}</span>
                                            <span>{{ \Carbon\Carbon::parse($ep->air_date)->format('d/m/Y') }}</span>
                                        </div>
                                        {{-- <i class="fas fa-play btn-play-ep"></i> --}}
                                    </li>
                                @endforeach
                            @else
                                <li>Nenhum episódio disponível para esta temporada.</li>
                            @endif
                        </ul>
                    </div>
                @endforeach
            @else
                <p style="color: var(--cor-texto-claro); text-align:center;">Nenhuma temporada ou episódio
                    disponível.</p>
            @endif
        </div>

        <p style="color: var(--cor-texto-claro); text-align:center;" id="placeholderEpisodios" class="hidden">
            Selecione uma temporada para ver os episódios.
        </p>
    </section>


    <section class="sessao-generos-serie">
        <div class="cabecalho-sessao">
            <h2 class="titulo-sessao">Gêneros</h2>
        </div>
        <div class="generos-container-serie">
            @if(isset($genres) && count($genres) > 0)
                @foreach ($genres as $genre)
                    <a href="{{ route('genres.show', ['slug' => $genre->slug]) }}" class="genero-btn-serie">
                        {{ $genre->name }}
                    </a>
                @endforeach
            @else
                <span class="genero-btn-serie">Sem gêneros disponíveis</span>
            @endif
        </div>
    </section>

    <section class="sessao-relacionados">
        <div class="cabecalho-sessao">
            <h2 class="titulo-sessao">Séries Relacionadas</h2>
        </div>
        <div class="series-container">
            @if(isset($relatedSeries) && count($relatedSeries) > 0)
                @foreach ($relatedSeries as $relatedS)
                    @php
                        $rating5_related = ($relatedS->rating ?? 0) / 2;
                        $fullStars_related = floor($rating5_related);
                        $halfStar_related = ($rating5_related - $fullStars_related) >= 0.5;
                        $emptyStars_related = 5 - $fullStars_related - ($halfStar_related ? 1 : 0);
                    @endphp
                    <a href="{{ route('serie.show', ['slug' => $relatedS->slug]) }}" class="filme"
                        data-titulo-completo="{{ $relatedS->title }}" data-ano="{{ $relatedS->year }}"
                        data-avaliacao="{{ number_format($rating5_related, 1) }}">
                        <img src="{{ $relatedS->poster_url ?? 'https://placehold.co/150x220/cccccc/000000?text=Poster' }}"
                            alt="{{ $relatedS->title }}"
                            onerror="this.onerror=null;this.src='https://placehold.co/150x220/cccccc/000000?text=Poster';">
                        <div class="card-overlay">
                            <div class="overlay-titulo">{{ $relatedS->title }}</div>
                            <div class="overlay-ano">{{ $relatedS->year }}</div>
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
                @endforeach
            @else
                <p style="color: var(--cor-texto-claro);">Nenhuma série relacionada disponível.</p>
            @endif
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
                        body: new URLSearchParams({ id, type })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (button && icon && label) {
                                if (data.added) {
                                    button.classList.add('salvo');
                                    icon.classList.remove('far');
                                    icon.classList.add('fas');
                                    label.textContent = 'Salvo';
                                } else {
                                    button.classList.remove('salvo');
                                    icon.classList.remove('fas');
                                    icon.classList.add('far');
                                    label.textContent = 'Salvar';
                                }
                            }

                            showToast(data.message);
                        });
                });
            });

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

    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    {{-- Se você não for mais usar Plyr.io para nenhum tipo de vídeo, pode remover o script do Plyr. --}}
    {{--
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script> --}}
    {{--
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" /> --}}

    <script>
        let hls; // Instância do HLS.js
        // let plyrInstance = null; // Removido, pois vamos usar o player personalizado
        let activePlayerWrapper = null; // Para gerenciar o player personalizado ativo
        let temporadaAtivaId = null;
        let episodioAtualDados = null; // Guarda os dados do episódio atual

        // Elementos DOM principais (alguns são obtidos dentro das funções quando necessário)
        const modalPlayer = document.getElementById("modalPlayer");
        const playerDestaqueArea = document.getElementById("playerDestaqueArea");
        const controlesNavegacaoEpisodio = document.getElementById("controlesNavegacaoEpisodio");
        const btnEpAnterior = document.getElementById("btnEpAnterior");
        const btnEpProximo = document.getElementById("btnEpProximo");

        function abrirModalPlayer(tituloEpisodio) {
            if (modalPlayer) {
                modalPlayer.style.display = "flex";
                const modalTitle = document.getElementById('modalPlayerTitle');
                if (tituloEpisodio) {
                    modalTitle.textContent = `Opções para: ${tituloEpisodio}`;
                } else {
                    modalTitle.textContent = 'Escolha uma Opção de Player';
                }
            }
            // Pausa o player personalizado se estiver ativo
            if (activePlayerWrapper) {
                const videoElement = activePlayerWrapper.querySelector('video');
                if (videoElement && !videoElement.paused) {
                    videoElement.pause();
                }
            }
        }

        function fecharModalPlayer() {
            if (modalPlayer) {
                modalPlayer.style.display = "none";
            }
        }

        function updateSliderBackground(sliderElement) {
            if (!sliderElement) return;
            const value = parseFloat(sliderElement.value);
            let min = parseFloat(sliderElement.min) || 0;
            let max = parseFloat(sliderElement.max) || 100;

            if (sliderElement.classList.contains('volume-slider')) {
                max = 1;
            }

            const percentage = ((value - min) / (max - min)) * 100;
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--cor-primaria').trim() || '#ffc107';
            const trackColor = getComputedStyle(document.documentElement).getPropertyValue('--cor-trilho-slider').trim() || 'rgba(255, 255, 255, 0.3)';

            sliderElement.style.background = `linear-gradient(to right, ${primaryColor} ${percentage}%, ${trackColor} ${percentage}%)`;
        }

        function adicionarEventosPlayer(videoElement, playerWrapper) {
            const playPauseBtn = playerWrapper.querySelector('.btn-playpause');
            const iconPlay = playPauseBtn.querySelector('.icon-play');
            const iconPause = playPauseBtn.querySelector('.icon-pause');

            const centralPlayPauseOverlay = playerWrapper.querySelector('.player-overlay-center-playpause');
            const btnCentralPlayPause = centralPlayPauseOverlay ? centralPlayPauseOverlay.querySelector('.btn-central-playpause') : null;
            const iconCentralPlay = btnCentralPlayPause ? btnCentralPlayPause.querySelector('.icon-central-play') : null;
            const iconCentralPause = btnCentralPlayPause ? btnCentralPlayPause.querySelector('.icon-central-pause') : null;

            const backwardBtn = playerWrapper.querySelector('.btn-backward');
            const forwardBtn = playerWrapper.querySelector('.btn-forward');

            const progressBar = playerWrapper.querySelector('.progress-bar');
            const timeDisplay = playerWrapper.querySelector('.time-display');

            const volumeBtn = playerWrapper.querySelector('.btn-volume');
            const iconVolumeOn = volumeBtn.querySelector('.icon-volume-on');
            const iconVolumeOff = volumeBtn.querySelector('.icon-volume-off');
            const volumeSlider = playerWrapper.querySelector('.volume-slider');

            const fullscreenBtn = playerWrapper.querySelector('.btn-fullscreen');
            const iconFullscreenEnter = fullscreenBtn.querySelector('.icon-fullscreen-enter');
            const iconFullscreenExit = fullscreenBtn.querySelector('.icon-fullscreen-exit');

            const controlsBar = playerWrapper.querySelector('.player-custom-controls-bar');
            let controlsTimeout;

            function formatTime(seconds) {
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = Math.floor(seconds % 60);

                if (h > 0) {
                    return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                } else {
                    return `${m}:${s.toString().padStart(2, '0')}`;
                }
            }

            const hideControls = () => {
                if (controlsBar) controlsBar.classList.add('controls-hidden');
            }
            const showControls = () => {
                if (controlsBar) controlsBar.classList.remove('controls-hidden');
                clearTimeout(controlsTimeout);
                if (!videoElement.paused) {
                    controlsTimeout = setTimeout(hideControls, 3000);
                }
            };

            const togglePlayPause = () => {
                if (videoElement.paused || videoElement.ended) {
                    videoElement.play().catch(e => console.warn("Player: Autoplay bloqueado ou erro:", e));
                } else {
                    videoElement.pause();
                }
            };

            if (btnCentralPlayPause) {
                btnCentralPlayPause.addEventListener('click', togglePlayPause);
            }
            playPauseBtn.addEventListener('click', togglePlayPause);
            videoElement.addEventListener('click', (e) => {
                if (e.target === videoElement && (!btnCentralPlayPause || !btnCentralPlayPause.contains(e.target))) {
                    togglePlayPause();
                }
            });

            videoElement.addEventListener('play', () => {
                iconPlay.classList.add('hidden');
                iconPause.classList.remove('hidden');
                if (iconCentralPlay && iconCentralPause) {
                    iconCentralPlay.classList.add('hidden');
                    iconCentralPause.classList.remove('hidden');
                }
                playerWrapper.classList.remove('paused-state');
                showControls();
            });
            videoElement.addEventListener('pause', () => {
                iconPause.classList.add('hidden');
                iconPlay.classList.remove('hidden');
                if (iconCentralPlay && iconCentralPause) {
                    iconCentralPause.classList.add('hidden');
                    iconCentralPlay.classList.remove('hidden');
                }
                playerWrapper.classList.add('paused-state');
                clearTimeout(controlsTimeout);
                if (controlsBar) controlsBar.classList.remove('controls-hidden');
            });

            backwardBtn.addEventListener('click', () => videoElement.currentTime = Math.max(0, videoElement.currentTime - 10));
            forwardBtn.addEventListener('click', () => videoElement.currentTime = Math.min(videoElement.duration || 0, videoElement.currentTime + 10));

            videoElement.addEventListener('loadedmetadata', () => {
                if (videoElement.duration && isFinite(videoElement.duration)) {
                    progressBar.max = videoElement.duration;
                    timeDisplay.textContent = `${formatTime(0)} / ${formatTime(videoElement.duration)}`;
                } else {
                    timeDisplay.textContent = `${formatTime(0)} / --:--`;
                    progressBar.max = 0;
                    progressBar.value = 0;
                }
                updateSliderBackground(progressBar);
            });
            videoElement.addEventListener('timeupdate', () => {
                progressBar.value = videoElement.currentTime;
                if (videoElement.duration && isFinite(videoElement.duration)) {
                    timeDisplay.textContent = `${formatTime(videoElement.currentTime)} / ${formatTime(videoElement.duration)}`;
                } else {
                    timeDisplay.textContent = `${formatTime(videoElement.currentTime)} / --:--`;
                }
                updateSliderBackground(progressBar);
            });
            progressBar.addEventListener('input', () => {
                videoElement.currentTime = progressBar.value;
                updateSliderBackground(progressBar);
            });

            const updateVolumeUI = () => {
                const isMuted = videoElement.muted || videoElement.volume === 0;
                iconVolumeOn.classList.toggle('hidden', isMuted);
                iconVolumeOff.classList.toggle('hidden', !isMuted);
                volumeSlider.value = videoElement.muted ? 0 : videoElement.volume;
                updateSliderBackground(volumeSlider);
            };
            volumeBtn.addEventListener('click', () => {
                videoElement.muted = !videoElement.muted;
                if (!videoElement.muted && videoElement.volume === 0) videoElement.volume = 0.5;
            });
            volumeSlider.addEventListener('input', (e) => {
                videoElement.volume = parseFloat(e.target.value);
                videoElement.muted = videoElement.volume === 0;
                updateSliderBackground(volumeSlider);
            });
            videoElement.addEventListener('volumechange', updateVolumeUI);

            const updateFullscreenUI = () => {
                const isFullscreen = document.fullscreenElement === playerWrapper || document.webkitFullscreenElement === playerWrapper || document.msFullscreenElement === playerWrapper;
                iconFullscreenEnter.classList.toggle('hidden', isFullscreen);
                iconFullscreenExit.classList.toggle('hidden', !isFullscreen);
                playerWrapper.classList.toggle('fullscreen-active', isFullscreen);
                if (isFullscreen) showControls();
            };
            fullscreenBtn.addEventListener('click', () => {
                if (!document.fullscreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                    if (playerWrapper.requestFullscreen) playerWrapper.requestFullscreen();
                    else if (playerWrapper.webkitRequestFullscreen) playerWrapper.webkitRequestFullscreen();
                    else if (playerWrapper.msRequestFullscreen) playerWrapper.msRequestFullscreen();
                } else {
                    if (document.exitFullscreen) document.exitFullscreen();
                    else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
                    else if (document.msExitFullscreen) document.msExitFullscreen();
                }
            });
            document.addEventListener('fullscreenchange', updateFullscreenUI);
            document.addEventListener('webkitfullscreenchange', updateFullscreenUI);
            document.addEventListener('msfullscreenchange', updateFullscreenUI);

            playerWrapper.addEventListener('mousemove', showControls);
            playerWrapper.addEventListener('mouseenter', showControls);
            playerWrapper.addEventListener('mouseleave', () => {
                if (!videoElement.paused) controlsTimeout = setTimeout(hideControls, 500);
            });

            updateVolumeUI();
            updateSliderBackground(progressBar);
            showControls();
            activePlayerWrapper = playerWrapper;
            if (videoElement.paused) {
                playerWrapper.classList.add('paused-state');
                iconPause.classList.add('hidden');
                iconPlay.classList.remove('hidden');
                if (iconCentralPlay && iconCentralPause) {
                    iconCentralPause.classList.add('hidden');
                    iconCentralPlay.classList.remove('hidden');
                }
            } else {
                playerWrapper.classList.remove('paused-state');
                iconPlay.classList.add('hidden');
                iconPause.classList.remove('hidden');
                if (iconCentralPlay && iconCentralPause) {
                    iconCentralPlay.classList.add('hidden');
                    iconCentralPause.classList.remove('hidden');
                }
            }
        }


        function carregarOpcoesPlayerParaEpisodio(epElemento) {
            const linksJson = epElemento.dataset.links;
            const epTitulo = epElemento.dataset.epTitulo;
            const epNumero = epElemento.dataset.epNumero;

            episodioAtualDados = {
                numero: parseInt(epNumero),
                titulo: epTitulo,
                links: JSON.parse(linksJson || "[]"),
                elementoLi: epElemento
            };

            const opcoesPlayerModal = document.getElementById('opcoesPlayerModal');
            const noOptionsMsg = document.getElementById('noPlayerOptionsMsg');
            opcoesPlayerModal.querySelectorAll('.player-option-btn').forEach(btn => btn.remove());

            if (episodioAtualDados.links && episodioAtualDados.links.length > 0) {
                if (noOptionsMsg) noOptionsMsg.classList.add('hidden');
                episodioAtualDados.links.forEach(link => {
                    const button = document.createElement('button');
                    button.classList.add('player-option-btn');
                    button.textContent = `${link.name || 'Opção'} ${link.quality ? '- ' + link.quality : ''}`;
                    button.dataset.url = link.url;
                    button.dataset.type = link.type || 'embed';
                    button.onclick = function () { iniciarPlayer(this); };
                    opcoesPlayerModal.insertBefore(button, noOptionsMsg);
                });
            } else {
                if (noOptionsMsg) noOptionsMsg.classList.remove('hidden');
            }
            abrirModalPlayer(`Ep. ${epNumero}: ${epTitulo}`);
        }

        function iniciarPlayer(buttonElement) {
            const url = buttonElement.dataset.url;
            const type = buttonElement.dataset.type;

            if (!playerDestaqueArea) {
                console.error("Elemento #playerDestaqueArea não encontrado.");
                fecharModalPlayer();
                return;
            }

            // Limpeza do player anterior
            if (hls) { hls.destroy(); hls = null; }
            const playerExistente = playerDestaqueArea.querySelector('iframe, .custom-player-wrapper'); // .plyr removido
            if (playerExistente) { playerExistente.remove(); }
            activePlayerWrapper = null;

            const serieBackdropInicial = document.getElementById("serieBackdropInicial");
            const serieDestaqueInfoSobreposta = document.getElementById("serieDestaqueInfoSobreposta"); // Se existir
            if (serieBackdropInicial) serieBackdropInicial.classList.add('hidden');
            if (serieDestaqueInfoSobreposta) serieDestaqueInfoSobreposta.classList.add('hidden');


            if (type === 'embed') {
                const iframe = document.createElement('iframe');
                iframe.src = url;
                iframe.setAttribute('frameborder', '0');
                iframe.setAttribute('allowfullscreen', 'true');
                iframe.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
                iframe.style.width = '100%'; // Garante que o iframe ocupe o espaço
                iframe.style.height = '100%';
                playerDestaqueArea.appendChild(iframe);
            } else if (type === 'mp4' || type === 'm3u8') {
                const wrapper = document.createElement('div');
                wrapper.className = 'custom-player-wrapper';

                const video = document.createElement('video');
                video.id = 'videoPlayerInstanceSerie';
                video.setAttribute('playsinline', '');
                video.controls = false;
                wrapper.appendChild(video);

                const centralOverlayHTML = `
                                                                                <div class="player-overlay-center-playpause">
                                                                                    <button class="btn-central-playpause" title="Play/Pause">
                                                                                        <svg class="icon-central-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                                                        <svg class="icon-central-pause hidden" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                                                                                    </button>
                                                                                </div>`;
                wrapper.insertAdjacentHTML('beforeend', centralOverlayHTML);

                const controlsContainer = document.createElement('div');
                controlsContainer.className = 'player-custom-controls-container';

                const svgPlay = '<svg class="icon-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>';
                const svgPause = '<svg class="icon-pause hidden" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>';
                const svgBackward = '<svg viewBox="0 0 24 24"><path d="M11.9 7.5c-3.53 0-6.43 2.61-6.92 6H7.8c.41-2.27 2.5-4 4.9-4s4.5 1.73 4.9 4h2.82c-.49-3.39-3.39-6-6.92-6zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h-1l-2 2.5 2 2.5h1v-1.25L10.25 13 11 12.25V11zm4 0h-1l-2 2.5 2 2.5h1v-1.25L14.25 13 15 12.25V11z"/></svg>';
                const svgForward = '<svg viewBox="0 0 24 24"><path d="M12.1 7.5c3.53 0 6.43 2.61 6.92 6h-2.82c-.41-2.27-2.5-4-4.9-4s-4.5 1.73-4.9 4H4.08c.49-3.39 3.39-6 6.92-6zM12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm1-13h1l2 2.5-2 2.5h-1v-1.25L13.75 13 13 12.25V11zm-4 0h1l2 2.5-2 2.5h-1v-1.25L9.75 13 9 12.25V11z"/></svg>';
                const svgVolumeOn = '<svg class="icon-volume-on" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>';
                const svgVolumeOff = '<svg class="icon-volume-off hidden" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>';
                const svgFullscreenEnter = '<svg class="icon-fullscreen-enter" viewBox="0 0 24 24"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>';
                const svgFullscreenExit = '<svg class="icon-fullscreen-exit hidden" viewBox="0 0 24 24"><path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/></svg>';

                controlsContainer.innerHTML = `
                                                                                <div class="player-custom-controls-bar">
                                                                                    <div class="controls-left">
                                                                                        <button class="control-button btn-playpause" title="Play/Pause">${svgPlay}${svgPause}</button>
                                                                                        <button class="control-button btn-backward" title="Voltar 10s">${svgBackward}</button>
                                                                                        <button class="control-button btn-forward" title="Avançar 10s">${svgForward}</button>
                                                                                    </div>
                                                                                    <div class="controls-center">
                                                                                        <input type="range" class="progress-bar" value="0" min="0" step="0.1" title="Progresso">
                                                                                    </div>
                                                                                    <div class="controls-right">
                                                                                        <span class="time-display">0:00 / 0:00</span>
                                                                                        <div class="volume-control-wrapper">
                                                                                           <button class="control-button btn-volume" title="Volume">${svgVolumeOn}${svgVolumeOff}</button>
                                                                                           <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="1" title="Controle de Volume">
                                                                                        </div>
                                                                                        <button class="control-button btn-fullscreen" title="Tela Cheia">${svgFullscreenEnter}${svgFullscreenExit}</button>
                                                                                    </div>
                                                                                </div>`;
                wrapper.appendChild(controlsContainer);
                playerDestaqueArea.appendChild(wrapper);

                if (type === 'm3u8' && Hls.isSupported()) {
                    hls = new Hls();
                    hls.loadSource(url);
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MANIFEST_PARSED, () => {
                        video.play().catch(() => { console.warn("HLS Autoplay foi bloqueado ou houve um erro inicial."); });
                    });
                    hls.on(Hls.Events.ERROR, function (event, data) {
                        console.error('HLS.js error:', data);
                        if (data.fatal) {
                            switch (data.type) {
                                case Hls.ErrorTypes.NETWORK_ERROR:
                                    console.error("HLS: erro de rede fatal", data);
                                    // Tentar recuperar, se aplicável, ou mostrar mensagem
                                    break;
                                case Hls.ErrorTypes.MEDIA_ERROR:
                                    console.error("HLS: erro de mídia fatal", data);
                                    hls.recoverMediaError(); // Tentar recuperar
                                    break;
                                default:
                                    hls.destroy(); // Destruir se for um erro não recuperável
                                    break;
                            }
                        }
                    });
                } else { // MP4
                    video.src = url;
                    video.addEventListener('canplay', () => {
                        video.play().catch(() => { console.warn("MP4 Autoplay foi bloqueado ou houve um erro inicial."); });
                    });
                    video.addEventListener('error', (e) => {
                        console.error('Erro no elemento de vídeo (MP4):', e);
                        // Mostrar mensagem de erro para o usuário, se necessário
                    });
                }
                adicionarEventosPlayer(video, wrapper);
            } else {
                console.error("Tipo de player desconhecido:", type);
                if (serieBackdropInicial) serieBackdropInicial.classList.remove('hidden');
                if (serieDestaqueInfoSobreposta) serieDestaqueInfoSobreposta.classList.remove('hidden');
                if (controlesNavegacaoEpisodio) controlesNavegacaoEpisodio.classList.add('hidden');
                alert("Erro: Tipo de player desconhecido.");
                fecharModalPlayer();
                return;
            }

            if (controlesNavegacaoEpisodio) {
                controlesNavegacaoEpisodio.classList.remove('hidden');
                atualizarBotoesNavegacao();
            }
            fecharModalPlayer();
        }

        function reabrirModalPlayerAtual() {
            if (episodioAtualDados) {
                const opcoesPlayerModal = document.getElementById('opcoesPlayerModal');
                const noOptionsMsg = document.getElementById('noPlayerOptionsMsg');
                opcoesPlayerModal.querySelectorAll('.player-option-btn').forEach(btn => btn.remove());

                if (episodioAtualDados.links && episodioAtualDados.links.length > 0) {
                    if (noOptionsMsg) noOptionsMsg.classList.add('hidden');
                    episodioAtualDados.links.forEach(link => {
                        const button = document.createElement('button');
                        button.classList.add('player-option-btn');
                        button.textContent = `Player (${link.name || 'Opção'}) ${link.quality ? '- ' + link.quality : ''}`;
                        button.dataset.url = link.url;
                        button.dataset.type = link.type || 'embed';
                        button.onclick = function () { iniciarPlayer(this); };
                        opcoesPlayerModal.insertBefore(button, noOptionsMsg);
                    });
                } else {
                    if (noOptionsMsg) noOptionsMsg.classList.remove('hidden');
                }
                abrirModalPlayer(`Ep. ${episodioAtualDados.numero}: ${episodioAtualDados.titulo}`);
            } else {
                alert("Nenhum episódio selecionado para mudar o player.");
            }
        }

        function atualizarBotoesNavegacao() {
            if (!temporadaAtivaId || !episodioAtualDados) {
                if (btnEpAnterior) btnEpAnterior.disabled = true;
                if (btnEpProximo) btnEpProximo.disabled = true;
                return;
            }
            const listaEpisodiosAtual = document.getElementById(temporadaAtivaId);
            if (!listaEpisodiosAtual) return;

            const todosEpisodiosLi = Array.from(listaEpisodiosAtual.querySelectorAll('li[data-ep-numero]')); // Apenas LIs de episódios
            const indiceEpAtual = todosEpisodiosLi.findIndex(li => li === episodioAtualDados.elementoLi);

            if (btnEpAnterior) btnEpAnterior.disabled = (indiceEpAtual <= 0);
            if (btnEpProximo) btnEpProximo.disabled = (indiceEpAtual < 0 || indiceEpAtual >= todosEpisodiosLi.length - 1);
        }

        function navegarEpisodio(direcao) {
            if (!temporadaAtivaId || !episodioAtualDados) return;
            const listaEpisodiosAtual = document.getElementById(temporadaAtivaId);
            if (!listaEpisodiosAtual) return;

            const todosEpisodiosLi = Array.from(listaEpisodiosAtual.querySelectorAll('li[data-ep-numero]'));
            const indiceEpAtual = todosEpisodiosLi.findIndex(li => li === episodioAtualDados.elementoLi);
            let proximoIndice = (direcao === 'anterior') ? indiceEpAtual - 1 : indiceEpAtual + 1;

            if (proximoIndice >= 0 && proximoIndice < todosEpisodiosLi.length) {
                const proximoEpElemento = todosEpisodiosLi[proximoIndice];
                // Primeiro, carrega as opções do novo episódio no modal, que também atualiza episodioAtualDados
                carregarOpcoesPlayerParaEpisodio(proximoEpElemento);

                // Se houver links, inicia o primeiro link automaticamente, ou o usuário escolhe no modal
                if (episodioAtualDados.links && episodioAtualDados.links.length > 0) {
                    // Para iniciar o primeiro player automaticamente:
                    // const primeiroBotaoPlayer = modalPlayer.querySelector('#opcoesPlayerModal .player-option-btn');
                    // if(primeiroBotaoPlayer) iniciarPlayer(primeiroBotaoPlayer);
                    // Senão, o modal já está aberto para o usuário escolher.
                } else {
                    // Se não houver links, limpa o player e mostra mensagem
                    if (activePlayerWrapper) {
                        const videoEl = activePlayerWrapper.querySelector('video');
                        if (videoEl) videoEl.pause(); // Pausa antes de limpar
                        activePlayerWrapper.remove();
                        activePlayerWrapper = null;
                    }
                    const iframeExistente = playerDestaqueArea.querySelector('iframe');
                    if (iframeExistente) iframeExistente.remove();

                    const serieBackdropInicial = document.getElementById("serieBackdropInicial");
                    if (serieBackdropInicial) serieBackdropInicial.classList.remove('hidden');
                    if (controlesNavegacaoEpisodio) controlesNavegacaoEpisodio.classList.add('hidden'); // Esconde controles de navegação se não há player
                    // Pode adicionar uma mensagem na área do playerDestaqueArea aqui também.
                    atualizarBotoesNavegacao(); // Atualiza os botões de anterior/próximo
                }
            }
        }

        window.onclick = function (event) {
            if (event.target == modalPlayer) {
                fecharModalPlayer();
            }
        }

        function carregarEpisodiosDaTemporada(targetListaId) {
            temporadaAtivaId = targetListaId;
            const todasListasEpisodios = document.querySelectorAll('#listaEpisodiosWrapper .lista-episodios');
            const placeholderEpisodios = document.getElementById('placeholderEpisodios');

            todasListasEpisodios.forEach(lista => {
                lista.classList.toggle('hidden', lista.id !== targetListaId);
            });

            const listaAtiva = document.getElementById(targetListaId);
            const temEpisodiosValidos = listaAtiva && listaAtiva.querySelector('li[data-links]'); // Verifica se há LIs com dados
            if (placeholderEpisodios) placeholderEpisodios.classList.toggle('hidden', !!temEpisodiosValidos);

            if (listaAtiva) {
                listaAtiva.querySelectorAll('li[data-links]').forEach(li => { // Apenas LIs com data-links
                    li.onclick = () => carregarOpcoesPlayerParaEpisodio(li);
                });
            }

            document.querySelectorAll('.btn-temporada').forEach(btn => {
                btn.classList.toggle('ativo', btn.dataset.temporadaTarget === targetListaId);
            });

            // Limpa o player ao mudar de temporada
            if (hls) { hls.destroy(); hls = null; }
            const playerExistente = playerDestaqueArea.querySelector('iframe, .custom-player-wrapper');
            if (playerExistente) { playerExistente.remove(); }
            activePlayerWrapper = null;
            episodioAtualDados = null; // Reseta dados do episódio atual

            const serieBackdropInicial = document.getElementById("serieBackdropInicial");
            const serieDestaqueInfoSobreposta = document.getElementById("serieDestaqueInfoSobreposta");
            if (serieBackdropInicial) serieBackdropInicial.classList.remove('hidden');
            if (serieDestaqueInfoSobreposta) serieDestaqueInfoSobreposta.classList.remove('hidden');
            if (controlesNavegacaoEpisodio) controlesNavegacaoEpisodio.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            // A função gerarEstrelas pode ser mantida se usada em outros lugares,
            // mas a lógica de estrelas principal já está no Blade.
            // function gerarEstrelas(container, avaliacao) { ... }

            const botoesTemporadasContainer = document.getElementById('botoesTemporadasContainer');
            if (botoesTemporadasContainer) {
                botoesTemporadasContainer.querySelectorAll('.btn-temporada').forEach(btn => {
                    btn.onclick = () => carregarEpisodiosDaTemporada(btn.dataset.temporadaTarget);
                });
                const primeiroBotaoTemporada = botoesTemporadasContainer.querySelector('.btn-temporada.ativo') || botoesTemporadasContainer.querySelector('.btn-temporada');
                if (primeiroBotaoTemporada) {
                    carregarEpisodiosDaTemporada(primeiroBotaoTemporada.dataset.temporadaTarget);
                } else {
                    const placeholderEpisodios = document.getElementById('placeholderEpisodios');
                    if (placeholderEpisodios) placeholderEpisodios.classList.remove('hidden');
                }
            }

            // Lógica de cards, se houver (não diretamente relacionada ao player)
            // const cards = document.querySelectorAll('.serie'); ...
        });

        // Teclas de atalho globais (mesma lógica da página de filmes)
        document.addEventListener('keydown', (event) => {
            if (!activePlayerWrapper) return;

            const videoElement = activePlayerWrapper.querySelector('video');
            if (!videoElement) return;

            const activeElement = document.activeElement;
            if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA' || activeElement.isContentEditable)) {
                return;
            }

            let preventDefault = true;
            switch (event.key) {
                case ' ':
                    // Tenta o botão central primeiro, depois o da barra de controles
                    const centralBtn = activePlayerWrapper.querySelector('.btn-central-playpause');
                    const barBtn = activePlayerWrapper.querySelector('.btn-playpause');
                    if (centralBtn && centralBtn.offsetParent !== null) { // Se o central está visível
                        centralBtn.click();
                    } else if (barBtn) {
                        barBtn.click();
                    }
                    break;
                case 'ArrowLeft':
                    videoElement.currentTime = Math.max(0, videoElement.currentTime - 5);
                    break;
                case 'ArrowRight':
                    videoElement.currentTime = Math.min(videoElement.duration || Infinity, videoElement.currentTime + 5);
                    break;
                case 'ArrowUp':
                    videoElement.volume = Math.min(1, videoElement.volume + 0.1);
                    break;
                case 'ArrowDown':
                    videoElement.volume = Math.max(0, videoElement.volume - 0.1);
                    break;
                case 'm':
                case 'M':
                    const volumeBtn = activePlayerWrapper.querySelector('.btn-volume');
                    if (volumeBtn) volumeBtn.click();
                    break;
                case 'f':
                case 'F':
                    const fullscreenBtn = activePlayerWrapper.querySelector('.btn-fullscreen');
                    if (fullscreenBtn) fullscreenBtn.click();
                    break;
                default:
                    preventDefault = false;
                    break;
            }
            if (preventDefault) event.preventDefault();
        });
    </script>
@endsection