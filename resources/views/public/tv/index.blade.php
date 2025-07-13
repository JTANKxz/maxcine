<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAXCINE TV</title>
    <!-- HLS.js -->
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        :root {
            --cor-primaria: #ffc107;
            --cor-secundaria: #303030;
            --cor-fundo: #050505;
            --cor-fundo-player: #000000;
            --cor-fundo-lista: #121212;
            --cor-fundo-item-canal: #1e1e1e;
            --cor-fundo-item-canal-hover: #2a2a2a;
            --cor-fundo-item-canal-ativo: var(--cor-primaria);
            --cor-texto: #f5f5f5;
            --cor-texto-claro: rgba(255, 255, 255, 0.7);
            --cor-texto-canal-ativo: #000000;
            --cor-borda: #444;
            --cor-sombra: rgba(0, 0, 0, 0.5);
            --altura-header: 60px;
            /* Altura do header */

            /* Variáveis para o modal de fonte */
            --modal-bg-light: #ffffff;
            --modal-text-light: #1f2937;
            /* gray-800 */
            --modal-title-text-light: #1f2937;
            /* gray-800 */

            --modal-bg-dark: #111827;
            /* gray-900 */
            --modal-text-dark: #f3f4f6;
            /* gray-100 */
            --modal-title-text-dark: #ffffff;
            /* white */

            /* Player Custom Controls */
            --player-controls-bg: rgba(0, 0, 0, 0.6);
            --player-icon-color: #fff;
            --player-progress-bg: rgba(255, 255, 255, 0.3);
            --player-progress-filled: var(--cor-primaria);
            --player-volume-bg: var(--player-progress-bg);
            --player-volume-filled: var(--cor-primaria);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cor-fundo);
            color: var(--cor-texto);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: var(--altura-header);
            /* Adiciona espaço para o header fixo */
        }

        /* Header Styles */
        .site-header {
            background-color: var(--cor-secundaria);
            padding: 0 1rem;
            /* py-0 px-4 */
            color: var(--cor-texto);
            position: fixed;
            /* Fixa o header no topo */
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            /* Garante que o header fique acima de outros conteúdos */
            height: var(--altura-header);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px var(--cor-sombra);
        }

        .logo-container {
            font-size: 1.5rem;
            /* text-2xl */
            font-weight: bold;
        }

        .logo-container a {
            color: var(--cor-primaria);
            text-decoration: none;
        }

        .hamburger-menu-button {
            background: none;
            border: none;
            color: var(--cor-primaria);
            cursor: pointer;
            padding: 0.5rem;
            /* p-2 */
            display: block;
            /* Mostra em mobile por padrão */
        }

        .hamburger-menu-button svg {
            width: 2rem;
            /* w-8 (32px) */
            height: 2rem;
            /* h-8 (32px) */
        }

        .navigation-menu {
            display: none;
            /* Escondido por padrão em mobile */
            flex-direction: column;
            position: absolute;
            top: var(--altura-header);
            /* Abaixo do header */
            left: 0;
            right: 0;
            background-color: var(--cor-secundaria);
            box-shadow: 0 2px 4px var(--cor-sombra);
            transform: translateY(-100%);
            /* Começa fora da tela (acima) */
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out, max-height 0.3s ease-in-out;
            opacity: 0;
            max-height: 0;
            overflow: hidden;
        }

        .navigation-menu.active {
            display: flex;
            transform: translateY(0);
            /* Desliza para a posição correta */
            opacity: 1;
            max-height: calc(100vh - var(--altura-header));
            /* Altura máxima para o menu */
            overflow-y: auto;
        }

        .navigation-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }

        .navigation-menu ul li a {
            display: block;
            padding: 1rem;
            /* p-4 */
            color: var(--cor-texto);
            text-decoration: none;
            border-bottom: 1px solid var(--cor-borda);
            transition: background-color 0.2s ease-in-out;
        }

        .navigation-menu ul li a:hover {
            background-color: var(--cor-fundo-item-canal-hover);
            color: var(--cor-primaria);
        }

        .navigation-menu ul li:last-child a {
            border-bottom: none;
        }


        /* Ajustes para desktop onde o menu pode ser horizontal */
        @media (min-width: 768px) {

            /* md breakpoint */
            .hamburger-menu-button {
                display: none;
                /* Esconde o hamburguer em telas maiores */
            }

            .navigation-menu {
                display: flex;
                flex-direction: row;
                /* Menu horizontal */
                position: static;
                /* Volta ao fluxo normal */
                background-color: transparent;
                box-shadow: none;
                transform: none;
                /* Remove a transformação */
                opacity: 1;
                max-height: none;
                overflow: visible;
            }

            .navigation-menu ul {
                display: flex;
            }

            .navigation-menu ul li a {
                padding: 0.5rem 1rem;
                /* py-2 px-4 */
                border-bottom: none;
                border-radius: 0.375rem;
                /* rounded-md */
            }

            .navigation-menu ul li a:hover {
                background-color: var(--cor-fundo-item-canal-hover);
                color: var(--cor-primaria);
            }
        }

        .main-container {
            display: flex;
            flex-direction: column; /* Padrão para mobile */
            flex-grow: 1; /* Ocupa o espaço vertical restante */
            overflow: hidden; /* Evita que o conteúdo interno estoure */
        }

        .player-section {
            background-color: var(--cor-fundo-player);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            width: 100%;
            box-sizing: border-box;
            flex-shrink: 0; /* Player section não deve encolher */
        }

        #currentChannelTitle {
            color: var(--cor-primaria);
            font-size: 1.5rem; /* text-2xl */
            line-height: 2rem; /* Correspondente ao text-2xl */
            font-weight: 600; /* font-semibold */
            margin-bottom: 0.5rem; /* mb-2 */
            text-align: center;
        }

        #videoPlayerContainer {
            width: 100%;
            max-width: 1280px; /* Limita a largura máxima do player em telas grandes */
            aspect-ratio: 16 / 9;
            background-color: var(--cor-fundo-player);
            position: relative;
            border-radius: 0.5rem; /* rounded-lg */
            overflow: hidden;
            box-shadow: 0 0 20px var(--cor-sombra);
        }
        
        #videoPlaceholder,
        #hlsPlayerWrapper, /* Wrapper para o player e controles customizados */
        #embedPlayer {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            border-radius: 0.5rem; 
        }

        #videoPlaceholder {
            object-fit: contain; 
            z-index: 10;
        }

        #initialPlayButtonContainer {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 11; /* Acima do placeholder */
            cursor: pointer;
        }
        
        #initialPlayButtonContainer.hidden-by-player { /* Nova classe para esconder quando o player customizado estiver ativo */
            display: none !important;
        }


        #initialPlayButtonContainer svg {
            width: 5rem; 
            height: 5rem; 
            color: var(--cor-primaria);
            opacity: 0.8;
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
        }

        #initialPlayButtonContainer:hover svg {
            opacity: 1;
            transform: scale(1.1);
        }

        #hlsPlayerWrapper {
            z-index: 5; /* Abaixo do placeholder/botão de play inicial */
        }
        #embedPlayer {
            z-index: 5;
        }
        
        video#hlsPlayer { 
            display: block; 
            background-color: #000;
            width: 100%;
            height: 100%;
            border-radius: 0.5rem; /* Mantém o arredondamento */
        }

        /* Custom Player Controls Styling */
        .player-controls {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: var(--player-controls-bg);
            padding: 8px 12px;
            display: flex;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            z-index: 2147483647; /* Max z-index */
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }

        #hlsPlayerWrapper:hover .player-controls,
        .player-controls.visible { /* Para manter visível durante interação com controles */
            opacity: 1;
            visibility: visible;
        }

        .player-controls button {
            background: none;
            border: none;
            color: var(--player-icon-color);
            cursor: pointer;
            padding: 6px;
            margin-right: 8px;
        }
        .player-controls button svg {
            width: 20px;
            height: 20px;
            display: block;
        }

        .progress-bar-container {
            flex-grow: 1;
            height: 8px;
            background-color: var(--player-progress-bg);
            border-radius: 4px;
            margin: 0 10px;
            cursor: pointer;
            position: relative;
        }
        .progress-bar-filled {
            width: 0%;
            height: 100%;
            background-color: var(--player-progress-filled);
            border-radius: 4px;
            transition: width 0.1s linear; /* Suaviza a atualização da barra */
        }
         .progress-bar-buffer { /* Opcional: para mostrar buffer */
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            z-index: -1;
        }


        .time-display {
            color: var(--player-icon-color);
            font-size: 0.85em;
            margin: 0 8px;
            min-width: 80px; /* Para evitar que o layout quebre */
            text-align: center;
        }

        .volume-controls {
            display: flex;
            align-items: center;
        }
        .volume-slider {
            width: 70px;
            height: 6px;
            background-color: var(--player-volume-bg);
            border-radius: 3px;
            margin-left: 5px;
            -webkit-appearance: none; /* Remove default Safari styles */
            appearance: none;
            cursor: pointer;
        }
        .volume-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 12px;
            height: 12px;
            background: var(--player-volume-filled);
            border-radius: 50%;
            cursor: pointer;
        }
        .volume-slider::-moz-range-thumb {
            width: 12px;
            height: 12px;
            background: var(--player-volume-filled);
            border-radius: 50%;
            cursor: pointer;
            border: none;
        }
        /* Esconder volume slider inicialmente, mostrar no hover do botão de volume */
        .volume-slider {
            display: none;
        }
        .volume-controls:hover .volume-slider {
            display: inline-block;
        }


        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top-color: var(--cor-primaria);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 15; /* Acima do vídeo, abaixo dos controles se necessário */
        }
        @keyframes spin {
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }


        .sidebar-section {
            background-color: var(--cor-fundo-lista);
            padding: 1rem;
            display: flex;
            flex-direction: column;
            width: 100%;
            box-sizing: border-box;
            flex-grow: 1; /* Permite que a sidebar ocupe o espaço vertical restante no mobile */
            min-height: 0; /* Importante para flex children em container com overflow/altura definida */
            overflow: hidden; /* A rolagem será gerenciada pelo #channelList */
        }
        
        #searchInput {
            background-color: var(--cor-fundo-item-canal);
            color: var(--cor-texto);
            border: 1px solid var(--cor-borda);
            border-radius: 0.375rem; /* rounded-md */
            padding: 0.75rem; /* p-3 */
            margin-bottom: 1rem; /* mb-4 */
            width: 100%;
            box-sizing: border-box; /* Garante que padding não aumente a largura total */
            flex-shrink: 0; /* Input não deve encolher */
        }

        #searchInput:focus {
            outline: none;
            border-color: var(--cor-primaria);
            box-shadow: 0 0 0 2px var(--cor-primaria);
        }

        #channelList {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-y: auto; /* Rolagem para a lista de canais */
            flex-grow: 1; /* Permite que a lista ocupe o espaço restante na sidebar */
        }

        #channelList>*+* { /* Styles for space-y-1 on #channelList */
            margin-top: 0.25rem;
        }

        .channel-item {
            background-color: var(--cor-fundo-item-canal);
            border-bottom: 1px solid var(--cor-borda);
            border-radius: 0.5rem; /* rounded-lg */
            padding: 0.75rem; /* p-3 */
            display: flex; /* flex */
            align-items: center; /* items-center */
            cursor: pointer; /* cursor-pointer */
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }

        .channel-item:last-child {
            border-bottom: none;
        }

        .channel-item:hover {
            background-color: var(--cor-fundo-item-canal-hover);
        }

        .channel-item.active {
            background-color: var(--cor-fundo-item-canal-ativo);
            color: var(--cor-texto-canal-ativo);
            font-weight: bold;
        }

        .channel-item.active .channel-logo {
            border: 2px solid var(--cor-texto-canal-ativo);
        }

        .channel-item.active .channel-name {
            color: var(--cor-texto-canal-ativo);
        }

        .channel-logo {
            width: 5rem; /* w-20 */
            height: 3rem; /* h-12 */
            border-radius: 0.375rem; /* rounded-md */
            margin-right: 0.75rem; /* mr-3 */
            object-fit: cover; /* object-cover */
            border: 2px solid transparent; /* border-2 border-transparent */
            flex-shrink: 0;
        }

        .channel-name {
            color: var(--cor-texto);
            flex-grow: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.875rem; /* text-sm */
            line-height: 1.25rem; /* text-sm */
        }

        /* Modal de Fonte de Vídeo */
        #sourceSelectModal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex; /* Será 'none' por padrão, JS controla */
            align-items: center;
            justify-content: center;
            z-index: 1050; /* Acima do header */
            padding: 1rem; /* Espaçamento para o conteúdo do modal em telas pequenas */
        }

        .source-modal-content {
            background-color: var(--modal-bg-light);
            padding: 1.5rem;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 28rem; /* max-w-md */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        @media (prefers-color-scheme: dark) {
            .source-modal-content {
                background-color: var(--modal-bg-dark);
            }
            .source-modal-title {
                color: var(--modal-title-text-dark);
            }
        }

        .source-modal-title {
            font-size: 1.125rem; /* text-lg */
            line-height: 1.75rem; /* text-lg */
            font-weight: 600; /* font-semibold */
            color: var(--modal-title-text-light);
            margin-bottom: 1rem; /* mb-4 */
        }

        #sourceOptionsList {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 60vh; /* Limita altura da lista de fontes */
            overflow-y: auto; /* Permite rolagem se muitas fontes */
        }

        #sourceOptionsList>*+* { /* Styles for space-y-2 on #sourceOptionsList */
            margin-top: 0.5rem;
        }

        .source-option-button {
            width: 100%;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            background-color: var(--cor-primaria);
            color: var(--cor-texto-canal-ativo);
            text-align: left;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s ease-in-out;
            font-weight: 500;
        }

        .source-option-button:hover {
            opacity: 0.8;
        }

        #closeSourceModal {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #dc2626; /* bg-red-600 */
            color: #ffffff; /* text-white */
            border-radius: 0.375rem; /* rounded */
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
            width: 100%; /* Botão de cancelar ocupa largura total */
        }

        #closeSourceModal:hover {
            background-color: #b91c1c; /* hover:bg-red-700 */
        }

        .action-button {
            background-color: var(--cor-secundaria);
            color: var(--cor-primaria);
            border: 1px solid var(--cor-primaria);
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
            margin-top: 1rem;
        }

        .action-button:hover {
            background-color: var(--cor-primaria);
            color: var(--cor-secundaria);
        }

        .action-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .hidden {
            display: none !important;
        }

        #playerMessage {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.8); /* Mais escuro para melhor leitura */
            color: var(--cor-texto);
            font-size: 1.25rem;
            text-align: center;
            padding: 1rem;
            z-index: 20; /* Acima do vídeo e spinner */
            border-radius: 0.5rem;
        }

        /* Media Queries para responsividade */
        @media (min-width: 640px) { /* sm breakpoint */
            .channel-logo {
                width: 6rem; /* sm:w-24 */
                height: 3.5rem; /* sm:h-14 */
            }
            .channel-name {
                font-size: 1rem; /* sm:text-base */
                line-height: 1.5rem; /* sm:text-base */
            }
        }

        @media (min-width: 1024px) { /* lg breakpoint */
            .main-container {
                flex-direction: row;
            }
            .player-section {
                width: 75%;
                 /* A altura será definida pelo aspect-ratio do player */
            }
             #currentChannelTitle {
                text-align: left;
            }
            .sidebar-section {
                width: 25%;
                flex-grow: 0; /* Não precisa crescer na horizontal */
                max-height: calc(100vh - var(--altura-header)); /* Sidebar com altura máxima e rolagem interna */
                min-height: auto; /* Reset min-height */
                 overflow-y: hidden; /* A rolagem principal é no #channelList */
            }
        }
    </style>
</head>

<body>
    <header class="site-header">
        <div class="logo-container">
            <a href="{{ route('tv.index') }}">MAXCINE TV</a> <!-- ATUALIZE O HREF SE NECESSÁRIO -->
        </div>
        <button class="hamburger-menu-button" id="hamburgerMenuButton" aria-label="Abrir menu" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <nav class="navigation-menu" id="navigationMenu">
            <ul>
                <li><a href="{{ route('home') }}">VOLTAR PARA O MAXCINE</a></li> <!-- ATUALIZE O HREF SE NECESSÁRIO -->
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <div class="player-section">
            <h2 id="currentChannelTitle">Selecione um Canal</h2>
            <div id="videoPlayerContainer">
                <img id="videoPlaceholder" src="https://placehold.co/1280x720/050505/303030?text=Player+de+TV"
                    alt="Video Placeholder">
                
                <div id="initialPlayButtonContainer"> <!-- Este é o botão de play grande inicial -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.625 3.125A1.125 1.125 0 0 1 9 15.125V8.875c0-.87.988-1.406 1.65-.874l5.625 3.124Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div id="hlsPlayerWrapper" class="hidden"> <!-- Wrapper para o vídeo e controles customizados -->
                    <video id="hlsPlayer"></video> <!-- Removido 'controls' -->
                    <div class="loading-spinner hidden"></div>
                    <div class="player-controls">
                        <button id="playPauseBtn">
                            <svg id="playIcon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                            <svg id="pauseIcon" class="hidden" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"></path></svg>
                        </button>
                        <div class="time-display">
                            <span id="currentTime">0:00</span> / <span id="duration">0:00</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-buffer"></div>
                            <div class="progress-bar-filled"></div>
                        </div>
                        <div class="volume-controls">
                            <button id="muteBtn">
                                <svg id="volumeHighIcon" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"></path></svg>
                                <svg id="volumeMutedIcon" class="hidden" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"></path></svg>
                            </button>
                            <input type="range" id="volumeSlider" class="volume-slider" min="0" max="1" step="0.05" value="1">
                        </div>
                        <button id="fullscreenBtn">
                            <svg viewBox="0 0 24 24"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"></path></svg>
                        </button>
                    </div>
                </div>

                <iframe id="embedPlayer" class="hidden" frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen></iframe>
                <div id="playerMessage" class="hidden">Nenhuma fonte de vídeo disponível.</div>
            </div>
            <div id="sourceSelectModal" class="hidden" style="display: none;">
                <div class="source-modal-content">
                    <h3 class="source-modal-title">Escolha uma fonte</h3>
                    <ul id="sourceOptionsList">
                    </ul>
                    <button id="closeSourceModal">Cancelar</button>
                </div>
            </div>
        </div>

        <div class="sidebar-section">
            <input type="search" id="searchInput" placeholder="Pesquisar canais...">
            <ul id="channelList">
                @foreach ($channels as $channel) <!-- MANTENHA SEU LOOP PHP/BLADE AQUI -->
                    <li class="channel-item" data-channel-name="{{ $channel->name }}"
                        data-channel-logo="{{ $channel->image_url }}" data-links='@json($channel->links)'>
                        <img src="{{ $channel->image_url }}" alt="{{ $channel->name }}" class="channel-logo"
                            onerror="this.onerror=null;this.src='https://placehold.co/80x48/1e1e1e/f5f5f5?text=Error';">
                        <span class="channel-name">{{ $channel->name }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const videoPlaceholder = document.getElementById('videoPlaceholder');
            const initialPlayButtonContainer = document.getElementById('initialPlayButtonContainer');
            const hlsPlayerWrapper = document.getElementById('hlsPlayerWrapper');
            const hlsPlayerElement = document.getElementById('hlsPlayer'); 
            const embedPlayerElement = document.getElementById('embedPlayer');
            const playerMessage = document.getElementById('playerMessage');

            const channelList = document.getElementById('channelList');
            const channelItems = channelList.querySelectorAll('.channel-item');
            const searchInput = document.getElementById('searchInput');
            const currentChannelTitle = document.getElementById('currentChannelTitle');

            const sourceSelectModal = document.getElementById('sourceSelectModal');
            const sourceOptionsList = document.getElementById('sourceOptionsList');
            const closeSourceModalButton = document.getElementById('closeSourceModal');

            const hamburgerMenuButton = document.getElementById('hamburgerMenuButton');
            const navigationMenu = document.getElementById('navigationMenu');

            let hlsInstance = null; 
            let currentSelectedChannelData = null;
            let activeChannelElement = null;

            // Custom Player Controls Elements
            const playerControls = hlsPlayerWrapper.querySelector('.player-controls');
            const playPauseBtn = document.getElementById('playPauseBtn');
            const playIcon = document.getElementById('playIcon');
            const pauseIcon = document.getElementById('pauseIcon');
            const progressBarContainer = hlsPlayerWrapper.querySelector('.progress-bar-container');
            const progressBarFilled = hlsPlayerWrapper.querySelector('.progress-bar-filled');
            const progressBarBuffer = hlsPlayerWrapper.querySelector('.progress-bar-buffer');
            const currentTimeEl = document.getElementById('currentTime');
            const durationEl = document.getElementById('duration');
            const muteBtn = document.getElementById('muteBtn');
            const volumeHighIcon = document.getElementById('volumeHighIcon');
            const volumeMutedIcon = document.getElementById('volumeMutedIcon');
            const volumeSlider = document.getElementById('volumeSlider');
            const fullscreenBtn = document.getElementById('fullscreenBtn');
            const loadingSpinner = hlsPlayerWrapper.querySelector('.loading-spinner');

            let controlsTimeout;


            if (hamburgerMenuButton && navigationMenu) {
                hamburgerMenuButton.addEventListener('click', () => {
                    const isExpanded = hamburgerMenuButton.getAttribute('aria-expanded') === 'true' || false;
                    hamburgerMenuButton.setAttribute('aria-expanded', !isExpanded);
                    navigationMenu.classList.toggle('active');
                });
            }
            
            function showCustomPlayerUI(show) {
                if (show) {
                    hlsPlayerWrapper.classList.remove('hidden');
                    initialPlayButtonContainer.classList.add('hidden-by-player'); // Esconde o botão de play grande
                } else {
                    hlsPlayerWrapper.classList.add('hidden');
                    initialPlayButtonContainer.classList.remove('hidden-by-player');
                }
            }


            function showPlayerMessage(message) {
                playerMessage.textContent = message;
                playerMessage.classList.remove('hidden');
                videoPlaceholder.classList.add('hidden');
                initialPlayButtonContainer.classList.add('hidden'); // Também esconde o botão de play grande
                showCustomPlayerUI(false); // Esconde a UI do player customizado
                
                if (hlsInstance) {
                    hlsInstance.destroy();
                    hlsInstance = null;
                }
                // hlsPlayerElement já está dentro do hlsPlayerWrapper que será escondido
                
                embedPlayerElement.classList.add('hidden');
                embedPlayerElement.src = ''; 
            }

            function resetPlayerView(showPlaceholder = true) {
                if (hlsInstance) {
                    hlsInstance.stopLoad();
                    hlsInstance.detachMedia();
                }
                showCustomPlayerUI(false); // Esconde a UI do player customizado
                hlsPlayerElement.pause(); // Pausa o elemento video diretamente
                // hlsPlayerElement.src = ''; // Não limpar src se for apenas esconder/pausar

                embedPlayerElement.classList.add('hidden');
                embedPlayerElement.src = ''; 

                playerMessage.classList.add('hidden');
                loadingSpinner.classList.add('hidden');


                if (showPlaceholder) {
                    videoPlaceholder.classList.remove('hidden');
                    initialPlayButtonContainer.classList.remove('hidden'); // Mostra o botão de play grande
                    initialPlayButtonContainer.classList.remove('hidden-by-player');
                    if (currentSelectedChannelData && currentSelectedChannelData.logo) {
                        videoPlaceholder.src = currentSelectedChannelData.logo;
                    } else if (activeChannelElement && activeChannelElement.dataset.channelLogo) {
                         videoPlaceholder.src = activeChannelElement.dataset.channelLogo;
                    }
                    else {
                        videoPlaceholder.src = "https://placehold.co/1280x720/050505/303030?text=Player+de+TV";
                    }
                } else {
                    videoPlaceholder.classList.add('hidden');
                    initialPlayButtonContainer.classList.add('hidden'); // Esconde o botão de play grande
                    initialPlayButtonContainer.classList.add('hidden-by-player');
                }
            }

            function playSelectedSource() {
                if (!currentSelectedChannelData || !currentSelectedChannelData.src || !currentSelectedChannelData.type) {
                    showPlayerMessage('Nenhuma fonte selecionada ou fonte inválida.');
                    resetPlayerView(true);
                    return;
                }

                resetPlayerView(false); // Esconde placeholder e botão de play inicial
                showCustomPlayerUI(true); // Mostra a UI do player customizado

                const { src, type, name: channelFullName } = currentSelectedChannelData; 
                currentChannelTitle.textContent = channelFullName; 

                if (type === 'm3u8') {
                    embedPlayerElement.classList.add('hidden');
                    embedPlayerElement.src = ''; 

                    if (Hls.isSupported()) {
                        if (hlsInstance) {
                            hlsInstance.destroy();
                        }
                        hlsInstance = new Hls({
                             enableWorker: true, // Melhora performance
                             lowLatencyMode: true, // Para streams de baixa latência, se aplicável
                             backBufferLength: 90 // Aumenta o buffer
                        });
                        loadingSpinner.classList.remove('hidden');
                        hlsInstance.loadSource(src);
                        hlsInstance.attachMedia(hlsPlayerElement);
                        
                        hlsInstance.on(Hls.Events.MANIFEST_PARSED, () => {
                            hlsPlayerElement.play().catch(e => {
                                console.error("Erro ao tentar dar play no HLS.js:", e);
                                showPlayerMessage('Erro ao iniciar o vídeo HLS. Verifique o console.');
                                resetPlayerView(true);
                                loadingSpinner.classList.add('hidden');
                            });
                        });
                        hlsInstance.on(Hls.Events.ERROR, (event, data) => {
                            console.error('HLS.js Error:', data);
                            loadingSpinner.classList.add('hidden');
                            if (data.fatal) {
                                let errorMsg = 'Erro fatal ao carregar o vídeo M3U8 (HLS.js).';
                                switch (data.type) {
                                    case Hls.ErrorTypes.NETWORK_ERROR:
                                        errorMsg = `Erro de rede: ${data.details}`;
                                        if (data.details === 'manifestLoadError' || data.details === 'manifestLoadTimeOut') {
                                            errorMsg = 'Falha ao carregar o manifesto do canal (verifique URL/CORS).';
                                        } else if (data.details === 'levelLoadError' || data.details === 'levelLoadTimeOut') {
                                            errorMsg = 'Falha ao carregar dados do canal.';
                                        } else if (data.details === 'fragLoadError' || data.details === 'fragLoadTimeOut') {
                                            errorMsg = 'Falha ao carregar segmento do vídeo.';
                                        }
                                        break;
                                    case Hls.ErrorTypes.MEDIA_ERROR:
                                        errorMsg = `Erro de mídia: ${data.details}`;
                                         if (data.details === 'manifestIncompatibleCodecsError') {
                                            errorMsg = 'Codecs do vídeo incompatíveis.';
                                        } else if (data.details === 'bufferAppendError' || data.details === 'bufferNudgeOnStall') {
                                            errorMsg = 'Problema ao carregar buffer do vídeo.';
                                        }
                                        break;
                                }
                                showPlayerMessage(errorMsg);
                                if (hlsInstance) hlsInstance.destroy(); 
                                hlsInstance = null;
                                resetPlayerView(true);
                            } else {
                                console.warn('HLS.js non-fatal error:', data.details);
                            }
                        });

                        // HLS.js events for loading spinner
                        hlsInstance.on(Hls.Events.BUFFER_APPENDING, () => loadingSpinner.classList.remove('hidden'));
                        hlsInstance.on(Hls.Events.BUFFER_EOS, () => loadingSpinner.classList.add('hidden'));
                        hlsInstance.on(Hls.Events.FRAG_BUFFERED, () => loadingSpinner.classList.add('hidden'));


                    } else if (hlsPlayerElement.canPlayType('application/vnd.apple.mpegurl')) {
                        hlsPlayerElement.src = src;
                        // Eventos do player nativo para spinner e erros devem ser adicionados aqui se este fallback for crucial
                        hlsPlayerElement.addEventListener('loadedmetadata', () => {
                             hlsPlayerElement.play().catch(e => {
                                console.error("Erro ao tentar dar play no HLS nativo:", e);
                                showPlayerMessage('Erro ao iniciar o vídeo HLS (nativo). Verifique o console.');
                                resetPlayerView(true);
                            });
                        });
                         hlsPlayerElement.addEventListener('error', (e) => {
                            console.error("Erro no player HLS nativo:", e);
                            showPlayerMessage('Erro ao carregar vídeo HLS (nativo).');
                            resetPlayerView(true);
                        });
                    } else {
                        showPlayerMessage('Seu navegador não suporta HLS.');
                        resetPlayerView(true);
                    }

                } else if (type === 'embed') {
                    embedPlayerElement.src = src;
                    embedPlayerElement.classList.remove('hidden');
                    showCustomPlayerUI(false); // Esconde UI customizada para embed
                    if (hlsInstance) {
                        hlsInstance.destroy();
                        hlsInstance = null;
                    }
                } else {
                    showPlayerMessage("Tipo de fonte desconhecido.");
                    resetPlayerView(true);
                }
            }

            function loadChannel(channelElement) {
                const channelName = channelElement.dataset.channelName;
                const channelLogo = channelElement.dataset.channelLogo;
                let links;
                try {
                    links = JSON.parse(channelElement.dataset.links);
                } catch (e) {
                    console.error("Erro ao parsear links do canal:", e, channelElement.dataset.links);
                    showPlayerMessage("Erro ao carregar fontes deste canal.");
                    currentChannelTitle.textContent = channelName || "Canal Inválido";
                    videoPlaceholder.src = channelLogo || "https://placehold.co/1280x720/050505/303030?text=Player+de+TV";
                    resetPlayerView(true);
                    return;
                }

                if (activeChannelElement) {
                    activeChannelElement.classList.remove('active');
                }
                channelElement.classList.add('active');
                activeChannelElement = channelElement;

                currentChannelTitle.textContent = channelName; 
                videoPlaceholder.src = channelLogo || "https://placehold.co/1280x720/050505/303030?text=Player+de+TV";
                
                currentSelectedChannelData = null; 
                if (hlsInstance) { 
                    hlsInstance.destroy();
                    hlsInstance = null;
                }
                resetPlayerView(true); // Mostra placeholder e botão de play grande


                if (!links || links.length === 0) {
                    showPlayerMessage("Nenhuma fonte disponível para este canal.");
                    // resetPlayerView(true) já foi chamado
                    currentSelectedChannelData = { name: channelName, logo: channelLogo, src: null, type: null }; 
                    return;
                }

                sourceOptionsList.innerHTML = '';
                links.forEach(link => {
                    if (!link.url || !link.type || !link.name) {
                        console.warn("Link inválido encontrado:", link);
                        return;
                    }
                    const li = document.createElement('li');
                    li.innerHTML = `<button class="source-option-button"
                        data-url="${link.url}"
                        data-type="${link.type.toLowerCase()}">${link.name} (${link.quality || 'Padrão'}) - ${link.type.toUpperCase()}</button>`;

                    li.querySelector('button').addEventListener('click', () => {
                        currentSelectedChannelData = {
                            name: `${channelName} - ${link.name}`, 
                            logo: channelLogo,
                            src: link.url,
                            type: link.type.toLowerCase()
                        };
                        sourceSelectModal.classList.add('hidden');
                        sourceSelectModal.style.display = 'none';
                        resetPlayerView(true); // Volta para o placeholder com o novo canal/fonte selecionado
                                               // O play ocorrerá ao clicar no botão de play grande ou no play dos controles
                    });
                    sourceOptionsList.appendChild(li);
                });

                sourceSelectModal.classList.remove('hidden');
                sourceSelectModal.style.display = 'flex';
                // resetPlayerView(true) já foi chamado
            }
            
            // --- Início Lógica dos Controles Customizados ---

            function togglePlayPause() {
                if (hlsPlayerElement.paused || hlsPlayerElement.ended) {
                    hlsPlayerElement.play().catch(e => console.error("Erro ao dar play:", e));
                } else {
                    hlsPlayerElement.pause();
                }
            }

            function updatePlayPauseIcon() {
                if (hlsPlayerElement.paused || hlsPlayerElement.ended) {
                    playIcon.classList.remove('hidden');
                    pauseIcon.classList.add('hidden');
                } else {
                    playIcon.classList.add('hidden');
                    pauseIcon.classList.remove('hidden');
                }
            }

            function formatTime(timeInSeconds) {
                const result = new Date(timeInSeconds * 1000).toISOString().slice(11, 19);
                return result.startsWith("00:") ? result.slice(3) : result;
            }

            function updateProgress() {
                const percent = (hlsPlayerElement.currentTime / hlsPlayerElement.duration) * 100;
                progressBarFilled.style.width = `${percent}%`;
                currentTimeEl.textContent = formatTime(hlsPlayerElement.currentTime);
                
                // Atualizar buffer (exemplo simples)
                if (hlsPlayerElement.buffered.length > 0) {
                    const bufferedEnd = hlsPlayerElement.buffered.end(hlsPlayerElement.buffered.length - 1);
                    const bufferPercent = (bufferedEnd / hlsPlayerElement.duration) * 100;
                    progressBarBuffer.style.width = `${bufferPercent}%`;
                }
            }
            
            function seek(event) {
                const rect = progressBarContainer.getBoundingClientRect();
                const offsetX = event.clientX - rect.left;
                const width = progressBarContainer.offsetWidth;
                const percent = offsetX / width;
                hlsPlayerElement.currentTime = percent * hlsPlayerElement.duration;
            }

            function toggleMute() {
                hlsPlayerElement.muted = !hlsPlayerElement.muted;
            }

            function updateMuteIcon() {
                if (hlsPlayerElement.muted || hlsPlayerElement.volume === 0) {
                    volumeHighIcon.classList.add('hidden');
                    volumeMutedIcon.classList.remove('hidden');
                    volumeSlider.value = 0;
                } else {
                    volumeHighIcon.classList.remove('hidden');
                    volumeMutedIcon.classList.add('hidden');
                    volumeSlider.value = hlsPlayerElement.volume;
                }
            }

            function handleVolumeChange() {
                hlsPlayerElement.volume = volumeSlider.value;
                hlsPlayerElement.muted = volumeSlider.value === "0"; // Muta se o volume for 0
            }
            
            function toggleFullscreen() {
                if (!document.fullscreenElement) {
                    hlsPlayerWrapper.requestFullscreen().catch(err => {
                        alert(`Erro ao tentar entrar em tela cheia: ${err.message} (${err.name})`);
                    });
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            }

            function showControls() {
                playerControls.classList.add('visible');
                clearTimeout(controlsTimeout);
                controlsTimeout = setTimeout(() => {
                    if (!hlsPlayerElement.paused) { // Não esconder se estiver pausado
                         playerControls.classList.remove('visible');
                    }
                }, 3000); // Esconder após 3 segundos de inatividade
            }
            
            function keepControlsVisible() { // Chamado ao interagir com os controles
                clearTimeout(controlsTimeout);
                playerControls.classList.add('visible');
            }
            function hideControlsOnMouseLeave() {
                 controlsTimeout = setTimeout(() => {
                    if (!hlsPlayerElement.paused && !playerControls.matches(':hover')) {
                         playerControls.classList.remove('visible');
                    }
                }, 500); // Pequeno delay para permitir mover para os controles
            }


            // Event Listeners para controles customizados
            playPauseBtn.addEventListener('click', togglePlayPause);
            hlsPlayerElement.addEventListener('play', updatePlayPauseIcon);
            hlsPlayerElement.addEventListener('pause', updatePlayPauseIcon);
            hlsPlayerElement.addEventListener('ended', updatePlayPauseIcon);
            
            hlsPlayerElement.addEventListener('loadedmetadata', () => {
                durationEl.textContent = formatTime(hlsPlayerElement.duration);
                updateProgress(); // Atualiza o tempo inicial
            });
            hlsPlayerElement.addEventListener('timeupdate', updateProgress);
            hlsPlayerElement.addEventListener('progress', updateProgress); // Para buffer

            progressBarContainer.addEventListener('click', seek);
            // Adicionar suporte a arrastar na barra de progresso (opcional, mais complexo)

            muteBtn.addEventListener('click', toggleMute);
            hlsPlayerElement.addEventListener('volumechange', updateMuteIcon);
            volumeSlider.addEventListener('input', handleVolumeChange);
            
            fullscreenBtn.addEventListener('click', toggleFullscreen);

            // Mostrar/Esconder controles
            hlsPlayerWrapper.addEventListener('mousemove', showControls);
            hlsPlayerWrapper.addEventListener('mouseleave', hideControlsOnMouseLeave);
            playerControls.addEventListener('mouseenter', keepControlsVisible); // Mantém visível se o mouse está sobre os controles
            playerControls.addEventListener('mouseleave', hideControlsOnMouseLeave); // Inicia timer para esconder se sair dos controles


            // Loading spinner events
            hlsPlayerElement.addEventListener('waiting', () => loadingSpinner.classList.remove('hidden'));
            hlsPlayerElement.addEventListener('playing', () => loadingSpinner.classList.add('hidden'));
            hlsPlayerElement.addEventListener('canplay', () => loadingSpinner.classList.add('hidden'));
            hlsPlayerElement.addEventListener('stalled', () => loadingSpinner.classList.remove('hidden'));


            // Keyboard controls
            document.addEventListener('keydown', (e) => {
                if (!hlsPlayerWrapper.classList.contains('hidden')) { // Só controla se o player estiver ativo
                    if (e.target === searchInput) return; // Não interfere na busca

                    if (e.key === ' ' || e.key === 'Spacebar' || e.key === 'k') {
                        e.preventDefault();
                        togglePlayPause();
                        showControls();
                    }
                    if (e.key === 'f') {
                        e.preventDefault();
                        toggleFullscreen();
                        showControls();
                    }
                    if (e.key === 'm') {
                        e.preventDefault();
                        toggleMute();
                        showControls();
                    }
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        hlsPlayerElement.currentTime -= 5;
                        showControls();
                    }
                    if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        hlsPlayerElement.currentTime += 5;
                        showControls();
                    }
                     if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        let newVolume = hlsPlayerElement.volume + 0.1;
                        if (newVolume > 1) newVolume = 1;
                        hlsPlayerElement.volume = newVolume;
                        volumeSlider.value = newVolume;
                        showControls();
                    }
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        let newVolume = hlsPlayerElement.volume - 0.1;
                        if (newVolume < 0) newVolume = 0;
                        hlsPlayerElement.volume = newVolume;
                        volumeSlider.value = newVolume;
                        showControls();
                    }
                }
            });


            // --- Fim Lógica dos Controles Customizados ---


            initialPlayButtonContainer.addEventListener('click', () => {
                if (!currentSelectedChannelData || !currentSelectedChannelData.src) {
                    if (activeChannelElement) {
                        const linksRaw = activeChannelElement.dataset.links;
                        if (linksRaw) {
                            try {
                                const links = JSON.parse(linksRaw);
                                if (links && links.length > 0) {
                                    sourceSelectModal.classList.remove('hidden');
                                    sourceSelectModal.style.display = 'flex';
                                } else {
                                    showPlayerMessage("Nenhuma fonte disponível para este canal.");
                                }
                            } catch (e) {
                                showPlayerMessage("Erro ao processar fontes do canal.");
                            }
                        } else {
                             showPlayerMessage("Nenhuma fonte disponível para este canal.");
                        }
                    } else {
                        showPlayerMessage("Por favor, selecione um canal primeiro.");
                    }
                    return;
                }
                playSelectedSource();
            });

            channelItems.forEach(item => {
                item.addEventListener('click', () => {
                    if (item !== activeChannelElement || sourceSelectModal.classList.contains('hidden')) {
                        loadChannel(item);
                    } 
                });
            });

            closeSourceModalButton.addEventListener('click', () => {
                sourceSelectModal.classList.add('hidden');
                sourceSelectModal.style.display = 'none';
                if (activeChannelElement && (!currentSelectedChannelData || !currentSelectedChannelData.src)) {
                     videoPlaceholder.src = activeChannelElement.dataset.channelLogo || "https://placehold.co/1280x720/050505/303030?text=Player+de+TV";
                     currentChannelTitle.textContent = activeChannelElement.dataset.channelName;
                     resetPlayerView(true);
                } else if (!activeChannelElement) {
                    currentChannelTitle.textContent = "Selecione um Canal";
                    videoPlaceholder.src = "https://placehold.co/1280x720/050505/303030?text=Player+de+TV";
                    resetPlayerView(true);
                }
            });

            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                channelItems.forEach(item => {
                    const channelName = item.dataset.channelName.toLowerCase();
                    if (channelName.includes(searchTerm)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });

            // Estado inicial
            if (channelItems.length > 0) {
                currentChannelTitle.textContent = "Selecione um Canal";
                videoPlaceholder.src = "https://placehold.co/1280x720/050505/303030?text=Player+de+TV";
                resetPlayerView(true);
            } else {
                currentChannelTitle.textContent = "Nenhum canal disponível";
                showPlayerMessage("Não há canais cadastrados na lista.");
            }
        });
    </script>
</body>
</html>
