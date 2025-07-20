<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $meta_title ?? (View::hasSection('title') ? trim($__env->yieldContent('title')) : config('app.name')) }}
    </title>
    <meta name="description"
        content="{{ $meta_description ?? 'Assista filmes e séries online grátis. Lançamentos, populares e muito mais.' }}">
    <meta name="keywords" content="{{ $meta_keywords ?? 'filmes, séries, assistir, online, streaming' }}">

    {{-- Open Graph / Facebook / WhatsApp --}}
    <meta property="og:title" content="{{ $meta_title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Assista agora no nosso site!' }}">
    <meta property="og:image" content="{{ $meta_image ?? asset('logo.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="video.movie">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta_title ?? config('app.name') }}">
    <meta name="twitter:description" content="{{ $meta_description ?? 'Assista agora no nosso site!' }}">
    <meta name="twitter:image" content="{{ $meta_image ?? asset('logo.jpg') }}">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('styles.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('extras.css') }}?v={{ time() }}">
    @yield('structured-data')
</head>


<body>
    <header class="header">
        <button class="menu-hamburger" onclick="toggleMenuDropdown()" aria-label="Abrir menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <a href="{{ route('home') }}">
            <h1 class="logo">MAXCINE</h1>
        </a>
        <div class="header-direita">
            <a href="#" id="search-icon" class="search-icon-link" aria-label="Abrir busca"><i
                    class="fa-solid fa-magnifying-glass"></i></a>
        </div>
    </header>
    <nav class="menu-dropdown" id="menuDropdown">
        <!-- Itens visíveis para todos -->
        <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i>Início</a>
        <a href="{{ route('movies') }}"><i class="fa-solid fa-film"></i>Filmes</a>
        <a href="{{ route('series') }}"><i class="fa-solid fa-tv"></i>Séries</a>
        <a href="{{ route('tv.index') }}"><i class="fa-solid fa-tv"></i>Canais de TV</a>
        <a href="#"><i class="fab fa-telegram"></i>Telegram (Em breve)</a>
        <a href="{{ route('download.app') }}"><i class="fa-solid fa-mobile-screen-button"></i>Baixar App</a>

        @auth
            <!-- Itens visíveis para usuários logados comuns -->
            <a href="{{ route('orders.search') }}"><i class="fa-solid fa-plus"></i>Fazer Pedido</a>
            @if(auth()->user()->is_admin)
                <!-- Visível apenas para administradores -->
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-lock"></i>Painel Admin</a>
            @endif
            <!-- Comum a todos autenticados -->
            <a href="{{ route('user.profile') }}"><i class="fa-solid fa-user"></i>Perfil</a>
            <a href="{{ route('auth.destroy') }}"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
        @else
            <!-- Visível apenas para visitantes (não logados) -->
            <a href="{{ route('login') }}"><i class="fa-solid fa-user"></i>Login</a>
        @endauth
    </nav>


    <div id="search-bar" class="search-bar hidden">
        <form action="{{ route('search.index') }}" method="GET">
            <div class="search-bar-container">
                <i class="fa-solid fa-magnifying-glass search-bar-icon"></i>
                <input type="text" name="q" placeholder="Pesquisar filmes e séries..." />
            </div>
        </form>
    </div>

    <div class="container-principal">
        <main class="main">
            <x-alert />
            @yield('content')
        </main>
        <footer class="footer">
            <p>© 2025 MAXCINE. Todos os direitos reservados.</p>
        </footer>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchIcon = document.getElementById("search-icon");
            const searchBar = document.getElementById("search-bar");
            const searchInputInBar = searchBar ? searchBar.querySelector('input[name="q"]') : null;
            const hamburgerIcon = document.querySelector(".menu-hamburger"); // Seleciona o botão
            const menuDropdown = document.getElementById("menuDropdown");

            if (searchIcon && searchBar) {
                searchIcon.addEventListener("click", (event) => {
                    event.preventDefault();
                    // Alterna a classe 'visible' em vez de 'hidden' para melhor controle da transição
                    if (searchBar.classList.contains("visible")) {
                        searchBar.classList.remove("visible");
                        // Adiciona 'hidden' após a transição para garantir display:none
                        setTimeout(() => {
                            if (!searchBar.classList.contains("visible")) { // Verifica novamente caso o usuário clique rápido
                                searchBar.classList.add("hidden");
                            }
                        }, 350); // Tempo da transição
                    } else {
                        searchBar.classList.remove("hidden"); // Remove hidden primeiro para permitir a transição
                        // Força um reflow para garantir que a remoção de 'hidden' seja processada antes de adicionar 'visible'
                        void searchBar.offsetWidth;
                        searchBar.classList.add("visible");
                    }


                    if (menuDropdown && searchBar.classList.contains("visible") && menuDropdown.classList.contains("aberto")) {
                        toggleMenuDropdown(); // Fecha o menu se a busca for aberta
                    }
                    if (searchInputInBar && searchBar.classList.contains("visible")) {
                        searchInputInBar.focus();
                    }
                });
            }

            // Função global para o onclick do HTML
            window.toggleMenuDropdown = function () {
                if (menuDropdown && hamburgerIcon) {
                    const isOpening = !menuDropdown.classList.contains("aberto");
                    menuDropdown.classList.toggle("aberto");
                    hamburgerIcon.classList.toggle("aberto");
                    hamburgerIcon.setAttribute('aria-expanded', isOpening);


                    if (searchBar && menuDropdown.classList.contains("aberto") && searchBar.classList.contains("visible")) {
                        searchBar.classList.remove("visible");
                        setTimeout(() => {
                            if (!searchBar.classList.contains("visible")) {
                                searchBar.classList.add("hidden");
                            }
                        }, 350);
                    }
                }
            }

            // Slider functionality
            let currentSlideIndex = 0;
            const slides = document.querySelectorAll('.slide');
            const totalSlides = slides.length;
            const barraProgresso = document.querySelector('.barra-progresso');
            const indicadoresContainer = document.querySelector('.indicadores');
            let slideInterval;
            const SLIDE_DURATION = 10000; // 10 seconds

            if (slides.length > 0 && barraProgresso && indicadoresContainer) {
                indicadoresContainer.innerHTML = ''; // Clear any existing indicators
                for (let i = 0; i < totalSlides; i++) {
                    const indicador = document.createElement('div');
                    indicador.classList.add('indicador');
                    if (i === 0) indicador.classList.add('ativo');
                    indicador.addEventListener('click', () => {
                        currentSlideIndex = i;
                        showSlide(currentSlideIndex);
                        resetSlideInterval();
                    });
                    indicadoresContainer.appendChild(indicador);
                }

                const indicadores = document.querySelectorAll('.indicador'); // Get newly created indicators

                function updateIndicadores(index) {
                    indicadores.forEach((indicador, i) => {
                        indicador.classList.toggle('ativo', i === index);
                    });
                }

                function showSlide(index) {
                    slides.forEach((slide) => {
                        slide.classList.remove('ativo');
                    });
                    if (slides[index]) {
                        slides[index].classList.add('ativo');
                    }
                    updateIndicadores(index);

                    if (barraProgresso) {
                        barraProgresso.style.transition = 'none'; // Reset transition for immediate width change
                        barraProgresso.style.width = '0%';
                        // Force reflow/repaint to ensure the transition reset applies before starting the new one
                        void barraProgresso.offsetWidth;
                        barraProgresso.style.transition = `width ${SLIDE_DURATION / 1000}s linear`; // Use SLIDE_DURATION
                        barraProgresso.style.width = '100%';
                    }
                }

                function nextSlide() {
                    currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
                    showSlide(currentSlideIndex);
                }

                function prevSlide() {
                    currentSlideIndex = (currentSlideIndex - 1 + totalSlides) % totalSlides;
                    showSlide(currentSlideIndex);
                }

                function startSlideShow() {
                    if (slideInterval) clearInterval(slideInterval);
                    slideInterval = setInterval(nextSlide, SLIDE_DURATION); // Use SLIDE_DURATION
                }

                function resetSlideInterval() {
                    clearInterval(slideInterval);
                    startSlideShow();
                }

                const slidesContainerElement = document.querySelector('.slides-container');
                let touchStartX = 0;
                let touchEndX = 0;

                if (slidesContainerElement) {
                    slidesContainerElement.addEventListener('touchstart', (event) => {
                        touchStartX = event.changedTouches[0].screenX;
                        clearInterval(slideInterval); // Pause slider on touch
                    }, { passive: true });

                    slidesContainerElement.addEventListener('touchend', (event) => {
                        touchEndX = event.changedTouches[0].screenX;
                        handleSwipeGesture();
                        resetSlideInterval(); // Resume slider after swipe or tap
                    }, { passive: true });
                }

                function handleSwipeGesture() {
                    const SWIPE_THRESHOLD = 50; // Minimum distance for a swipe
                    if (touchEndX < touchStartX - SWIPE_THRESHOLD) { // Swiped left
                        nextSlide();
                    }
                    if (touchEndX > touchStartX + SWIPE_THRESHOLD) { // Swiped right
                        prevSlide();
                    }
                }

                if (totalSlides > 0) {
                    showSlide(currentSlideIndex); // Show initial slide
                    startSlideShow(); // Start the slideshow
                }
            }

            // Card data handling (existing logic, seems fine)
            const cards = document.querySelectorAll('.filme, .serie');
            cards.forEach(card => {
                const tituloCompleto = card.dataset.tituloCompleto || card.querySelector('.overlay-titulo').textContent;
                const ano = card.dataset.ano;
                const avaliacao = parseFloat(card.dataset.avaliacao);

                const overlayTituloEl = card.querySelector('.overlay-titulo');
                const overlayAnoEl = card.querySelector('.overlay-ano');
                const estrelasContainer = card.querySelector('.overlay-avaliacao-estrelas');

                if (overlayTituloEl) overlayTituloEl.textContent = tituloCompleto;
                if (overlayAnoEl) overlayAnoEl.textContent = ano;

                if (estrelasContainer && !isNaN(avaliacao)) {
                    estrelasContainer.innerHTML = ''; // Clear existing stars
                    for (let i = 1; i <= 5; i++) {
                        const estrela = document.createElement('i');
                        if (i <= avaliacao) {
                            estrela.className = 'fas fa-star'; // Full star
                        } else if (i - 0.5 <= avaliacao) {
                            estrela.className = 'fas fa-star-half-alt'; // Half star
                        } else {
                            estrela.className = 'far fa-star'; // Empty star
                        }
                        estrelasContainer.appendChild(estrela);
                    }
                }
            });
        });

        // This function is not used by the current search bar implementation,
        // as the search is handled by a form submission.
        // It could be used for live search results if implemented.
        function searchContent() {
            const searchBar = document.getElementById("search-bar");
            const queryInput = searchBar ? searchBar.querySelector('input[name="q"]') : null;
            const query = queryInput ? queryInput.value : "";

            const resultsDiv = document.getElementById("search-results"); // Assuming a div with this ID exists for results

            if (!resultsDiv) return; // No place to show results

            if (query.length === 0) {
                resultsDiv.innerHTML = "";
                return;
            }
            if (query.length < 3) {
                resultsDiv.innerHTML = "<p style='color: var(--cor-texto-claro);'>Digite pelo menos 3 caracteres.</p>";
                return;
            }

            resultsDiv.innerHTML = "<p style='color: var(--cor-texto-claro);'>Buscando...</p>";
            console.log(`Buscando por: ${query}`);
            // Simulate API call
            setTimeout(() => {
                resultsDiv.innerHTML = `<p style='color: var(--cor-texto-claro);'>Nenhum resultado para "${query}". (Busca simulada)</p>`;
            }, 1000);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script>
        let hls; // Instância do HLS.js
        // let plyrInstance; // Removido se não estiver usando Plyr.io
        let activePlayerWrapper = null; // Para gerenciar o auto-hide dos controles e teclas de atalho

        const modalPlayer = document.getElementById("modalPlayer");
        const playerAreaContainer = document.getElementById("playerAreaContainer");
        // playerImagemFundo e btnPlayCentral são referenciados dentro de iniciarPlayer quando necessário
        const btnMudarPlayer = document.getElementById("btnMudarPlayer");

        function abrirModalPlayer() {
            if (modalPlayer) modalPlayer.style.display = "flex";
            const videoElement = playerAreaContainer.querySelector('video');
            if (videoElement && !videoElement.paused) {
                videoElement.pause();
            }
        }

        function fecharModalPlayer() {
            if (modalPlayer) modalPlayer.style.display = "none";
        }

        function updateSliderBackground(sliderElement) {
            if (!sliderElement) return;
            const value = parseFloat(sliderElement.value);
            let min = parseFloat(sliderElement.min) || 0;
            let max = parseFloat(sliderElement.max) || 100;

            if (sliderElement.classList.contains('volume-slider')) {
                max = 1; // Volume slider é de 0 a 1
            }

            const percentage = ((value - min) / (max - min)) * 100;
            // Tenta obter as cores das variáveis CSS, com fallbacks
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
                if (!videoElement.paused) { // Só agenda para esconder se estiver tocando
                    controlsTimeout = setTimeout(hideControls, 3000);
                }
            };

            const togglePlayPause = () => {
                if (videoElement.paused || videoElement.ended) {
                    videoElement.play().catch(e => console.warn("Autoplay blocked or error:", e));
                } else {
                    videoElement.pause();
                }
            };

            if (btnCentralPlayPause) {
                btnCentralPlayPause.addEventListener('click', togglePlayPause);
            }
            playPauseBtn.addEventListener('click', togglePlayPause);
            // Permite clicar no vídeo para play/pause, exceto se o clique for no botão central (que já tem seu próprio listener)
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
                playerWrapper.classList.remove('paused-state'); // Controla visibilidade do overlay central via CSS
                showControls();
            });
            videoElement.addEventListener('pause', () => {
                iconPause.classList.add('hidden');
                iconPlay.classList.remove('hidden');
                if (iconCentralPlay && iconCentralPause) {
                    iconCentralPause.classList.add('hidden');
                    iconCentralPlay.classList.remove('hidden');
                }
                playerWrapper.classList.add('paused-state'); // Controla visibilidade do overlay central via CSS
                clearTimeout(controlsTimeout);
                if (controlsBar) controlsBar.classList.remove('controls-hidden');
            });

            backwardBtn.addEventListener('click', () => videoElement.currentTime = Math.max(0, videoElement.currentTime - 10));
            forwardBtn.addEventListener('click', () => videoElement.currentTime = Math.min(videoElement.duration || 0, videoElement.currentTime + 10));

            videoElement.addEventListener('loadedmetadata', () => {
                if (videoElement.duration && isFinite(videoElement.duration)) { // Garante que duration é um número válido
                    progressBar.max = videoElement.duration;
                    timeDisplay.textContent = `${formatTime(0)} / ${formatTime(videoElement.duration)}`;
                } else { // Para streams ao vivo ou com duração desconhecida
                    timeDisplay.textContent = `${formatTime(0)} / --:--`;
                    progressBar.max = 0; // Ou algum valor simbólico se preferir
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
                // updateVolumeUI() será chamado automaticamente pelo evento 'volumechange'
            });
            volumeSlider.addEventListener('input', (e) => {
                videoElement.volume = parseFloat(e.target.value);
                videoElement.muted = videoElement.volume === 0;
                updateSliderBackground(volumeSlider);
                // O evento 'volumechange' também chamará updateVolumeUI, mas o updateSliderBackground aqui dá feedback imediato.
            });
            videoElement.addEventListener('volumechange', updateVolumeUI); // Atualiza UI em qualquer mudança de volume


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
                    else if (playerWrapper.webkitRequestFullscreen) playerWrapper.webkitRequestFullscreen(); // Safari
                    else if (playerWrapper.msRequestFullscreen) playerWrapper.msRequestFullscreen();     // IE11
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

            // Inicializações de UI
            updateVolumeUI();
            updateSliderBackground(progressBar);
            showControls();
            activePlayerWrapper = playerWrapper;
            // Garante que o estado inicial do overlay central e dos ícones esteja correto
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

        function iniciarPlayer(buttonElement) {
            const url = buttonElement.dataset.url;
            const type = buttonElement.dataset.type;

            if (hls) { hls.destroy(); hls = null; }
            playerAreaContainer.innerHTML = ''; // Limpa completamente a área do player
            activePlayerWrapper = null;

            // Obtém referências aos elementos originais da página (fora do player dinâmico)
            const playerImagemFundoOriginal = document.getElementById("playerImagemFundo");
            const btnPlayCentralOriginal = document.getElementById("btnPlayCentral");

            if (playerImagemFundoOriginal) playerImagemFundoOriginal.style.display = 'none';
            if (btnPlayCentralOriginal) btnPlayCentralOriginal.style.display = 'none';


            if (type === 'embed') {
                const iframe = document.createElement('iframe');
                iframe.src = url;
                iframe.setAttribute('frameborder', '0');
                iframe.setAttribute('allowfullscreen', 'true');
                iframe.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
                iframe.style.width = '100%';
                iframe.style.height = '100%';
                playerAreaContainer.appendChild(iframe);
            } else if (type === 'mp4' || type === 'm3u8') {
                const wrapper = document.createElement('div');
                wrapper.className = 'custom-player-wrapper';

                const video = document.createElement('video');
                video.id = 'videoPlayerInstance';
                video.setAttribute('playsinline', '');
                // video.autoplay = true; // Autoplay é melhor tratado após HLS carregar ou src ser definido
                video.controls = false;
                wrapper.appendChild(video);

                const centralOverlayHTML = `
                    <div class="player-overlay-center-playpause">
                        <button class="btn-central-playpause" title="Play/Pause">
                            <svg class="icon-central-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <svg class="icon-central-pause hidden" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </button>
                    </div>`;
                // Insere o overlay central depois do vídeo, mas dentro do wrapper
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
                playerAreaContainer.appendChild(wrapper);

                if (type === 'm3u8' && Hls.isSupported()) {
                    hls = new Hls();
                    hls.loadSource(url);
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MANIFEST_PARSED, () => {
                        video.play().catch(() => { console.warn("HLS Autoplay foi bloqueado ou houve um erro inicial."); });
                    });
                    hls.on(Hls.Events.ERROR, function (event, data) {
                        console.error('HLS.js error:', data);
                        // Você pode adicionar uma lógica para tentar recuperar ou mostrar uma mensagem ao usuário
                    });
                } else { // MP4 ou HLS não suportado (navegador lida)
                    video.src = url;
                    video.addEventListener('canplay', () => { // Tenta dar play quando o vídeo MP4 estiver pronto
                        video.play().catch(() => { console.warn("MP4 Autoplay foi bloqueado ou houve um erro inicial."); });
                    });
                }

                adicionarEventosPlayer(video, wrapper);
                // O play inicial é melhor tratado dentro dos listeners de HLS.Events.MANIFEST_PARSED ou 'canplay' para MP4
                // video.play().catch(() => {}); // Removido daqui para evitar play prematuro
            }

            if (btnMudarPlayer) btnMudarPlayer.classList.remove('hidden');
            fecharModalPlayer();
        }

        document.addEventListener('keydown', (event) => {
            if (!activePlayerWrapper) return;

            const videoElement = activePlayerWrapper.querySelector('video');
            if (!videoElement) return;

            const activeElement = document.activeElement;
            if (activeElement && (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA' || activeElement.isContentEditable)) {
                return;
            }

            let preventDefault = true; // Assumir que vamos prevenir o padrão
            switch (event.key) {
                case ' ':
                    const playPauseBtn = activePlayerWrapper.querySelector('.btn-playpause') || activePlayerWrapper.querySelector('.btn-central-playpause');
                    if (playPauseBtn) playPauseBtn.click();
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
                    preventDefault = false; // Não previne o padrão para outras teclas
                    break;
            }
            if (preventDefault) event.preventDefault();
        });
    </script>
</body>

</html>