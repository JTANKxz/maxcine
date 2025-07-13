<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Reset básico e configurações globais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Estilos de Paginação */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
            list-style: none;
        }

        .pagination li {
            margin: 0 3px;
        }

        .pagination li a,
        .pagination li span {
            /* Abrange links e spans (para itens desabilitados/ativos) */
            display: inline-block;
            padding: 8px 14px;
            color: #c0c0c0;
            background-color: #2a2a45;
            border: 1px solid #3a3a55;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            font-size: 0.9em;
        }

        .pagination li a:hover {
            background-color: #3a3a55;
            border-color: #9d4edd;
            color: #ffffff;
        }

        .pagination li.active span,
        .pagination li.active a {
            /* 'a' adicionado para cobrir casos onde Laravel usa 'a' mesmo para ativo */
            background-color: #9d4edd;
            border-color: #9d4edd;
            color: #ffffff;
            font-weight: bold;
            cursor: default;
        }

        .pagination li.disabled span,
        .pagination li.disabled a {
            color: #666;
            background-color: #222235;
            border-color: #2a2a45;
            cursor: not-allowed;
        }

        /* Para os ícones de "previous" e "next" se o Laravel os usar (geralmente usa &lsaquo; &rsaquo;) */
        .pagination .page-link[rel="prev"],
        .pagination .page-link[rel="next"] {
            font-weight: bold;
        }

        body {
            background-color: #1a1a2e;
            /* Fundo principal escuro */
            color: #e0e0e0;
            /* Cor do texto principal */
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            /* Evita rolagem horizontal no corpo */
        }

        /* Container principal do dashboard */
        .dashboard-container {
            display: flex;
            width: 100%;
        }

        /* Container da lista de checkboxes */
        .checkbox-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            /* espaçamento entre checkboxes */
            max-height: none;
            /* padrão sem limite */
            overflow-y: visible;
            /* padrão sem scroll */
        }

        /* Cada checkbox com label */
        .checkbox-container label {
            display: flex;
            align-items: center;
            cursor: pointer;
            min-width: 150px;
            /* largura mínima para organizar lado a lado */
        }

        /* Estilo do checkbox customizado */
        .checkbox-container input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #666;
            border-radius: 4px;
            margin-right: 8px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
        }

        /* Checkbox marcado */
        .checkbox-container input[type="checkbox"]:checked {
            background-color: #6f42c1;
            border-color: #6f42c1;
        }

        .checkbox-container input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 6px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        /* Texto do label */
        .checkbox-container label span {
            user-select: none;
        }

        /* --- RESPONSIVO --- */
        /* Para telas pequenas, limite altura e scroll vertical */
        @media (max-width: 600px) {
            .checkbox-container {
                flex-direction: column;
                /* empilha verticalmente */
                max-height: 160px;
                /* altura máxima para scroll */
                overflow-y: auto;
                border: 1px solid #444;
                padding: 8px;
                border-radius: 4px;
            }

            .checkbox-container label {
                min-width: auto;
                margin-bottom: 6px;
            }
        }


        /* Texto do label */
        .form-group label {
            user-select: none;
            /* evita seleção ao clicar no texto */
            cursor: pointer;
        }

        /* Barra lateral (Menu) */
        .sidebar {
            width: 260px;
            background-color: #161625;
            padding: 0;
            transition: width 0.3s ease, transform 0.3s ease;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            text-align: center;
            padding: 18px 0;
            height: 60px;
            margin-bottom: 10px;
            border-bottom: 1px solid #2a2a45;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-header h2 {
            color: #9d4edd;
            font-size: 1.8em;
            line-height: 1;
        }

        .sidebar-nav {
            padding-top: 10px;
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav>ul>li>a {
            border-left: 3px solid transparent;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 25px;
            color: #c0c0c0;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease, padding-left 0.3s ease;
        }

        .sidebar-nav li a .link-content {
            display: flex;
            align-items: center;
        }

        .sidebar-nav li a i:first-child {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        .submenu-indicator {
            font-size: 0.8em;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-nav li.submenu-active>a>.submenu-indicator {
            transform: rotate(180deg);
        }


        .sidebar-nav li a:hover {
            background-color: #2a2a45;
            color: #ffffff;
        }

        .sidebar-nav>ul>li>a:hover {
            padding-left: 30px;
        }


        .sidebar-nav>ul>li>a.active {
            background-color: #9d4edd30;
            color: #9d4edd;
            border-left-color: #9d4edd;
            font-weight: bold;
        }

        /* Estilos do Submenu */
        .submenu {
            list-style: none;
            padding-left: 0;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            background-color: #10101e;
            transition: max-height 0.35s cubic-bezier(0.25, 0.1, 0.25, 1),
                opacity 0.3s ease-in-out,
                margin-top 0.3s ease-in-out,
                margin-bottom 0.3s ease-in-out;
        }

        .sidebar-nav li.submenu-active>.submenu {
            max-height: 500px;
            opacity: 1;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .submenu li a {
            padding: 10px 25px 10px 45px;
            font-size: 0.9em;
            color: #a0a0a0;
        }

        .submenu li a i:first-child {
            margin-right: 10px;
            width: 15px;
            font-size: 0.9em;
        }

        .submenu li a:hover,
        .submenu li a.active {
            color: #9d4edd;
            background-color: #252538;
        }


        /* Conteúdo principal */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 260px;
            transition: margin-left 0.3s ease, width 0.3s ease;
            background-color: #1a1a2e;
            width: calc(100% - 260px);
            padding-top: 80px;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 260px;
            width: calc(100% - 260px);
            background-color: #1a1a2e;
            z-index: 990;
            padding: 0 20px;
            height: 60px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: left 0.3s ease, width 0.3s ease;
        }


        .main-header h1 {
            color: #e0e0e0;
            font-size: 1.6em;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #9d4edd;
            font-size: 1.8em;
            cursor: pointer;
            z-index: 1001;
        }

        .menu-toggle i {
            transition: transform 0.3s ease-in-out;
        }


        /* Cards */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background-color: #161625;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(157, 78, 221, 0.3);
        }

        .card-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .card-header i {
            font-size: 1.6em;
            color: #9d4edd;
            margin-right: 15px;
            padding: 10px;
            background-color: #9d4edd20;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .card-content {
            flex-grow: 1;
        }

        .card-content h3 {
            font-size: 1.1em;
            margin-bottom: 8px;
            color: #f0f0f0;
        }

        .card-content p {
            font-size: 1.5em;
            font-weight: bold;
            color: #9d4edd;
        }

        .card-footer {
            margin-top: 15px;
            font-size: 0.85em;
            color: #888;
            align-self: flex-start;
        }

        /* Seção de Gráficos */
        .charts-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            /* Ajustado minmax */
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-container {
            background-color: #161625;
            padding: 15px;
            /* Reduzido padding para mais espaço ao gráfico */
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            height: 320px;
            /* Altura padrão ajustada */
            position: relative;
            /* Importante para o canvas responsivo do Chart.js */
        }

        .chart-container h3 {
            margin-bottom: 10px;
            /* Reduzido margin */
            color: #e0e0e0;
            font-size: 1.1em;
            /* Reduzido tamanho da fonte */
            text-align: center;
        }

        /* O canvas do Chart.js se ajustará ao .chart-container se responsive:true e maintainAspectRatio:false */


        /* Tabelas */
        .table-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .table-section-header h3 {
            color: #e0e0e0;
            font-size: 1.3em;
            margin-bottom: 0;
        }

        .table-container {
            background-color: #161625;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #2a2a45;
            font-size: 0.9em;
            white-space: nowrap;
        }

        td:last-child,
        th:last-child {
            white-space: normal;
        }


        th {
            background-color: #2a2a45;
            color: #c0c0c0;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85em;
        }

        tbody tr {
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background-color: #222235;
        }

        td img {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            vertical-align: middle;
        }

        .status {
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.75em;
            font-weight: bold;
        }

        .status.active {
            background-color: #28a74530;
            color: #28a745;
        }

        .status.pending {
            background-color: #ffc10730;
            color: #ffc107;
        }

        .status.cancelled {
            background-color: #dc354530;
            color: #dc3545;
        }

        /* Formulários */
        .form-section {
            background-color: #161625;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .form-section h3 {
            margin-bottom: 20px;
            color: #e0e0e0;
            font-size: 1.3em;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #c0c0c0;
            font-weight: 500;
            font-size: 0.9em;
        }

        .form-control-like {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #2a2a45;
            background-color: #1f1f30;
            color: #e0e0e0;
            font-size: 0.95em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control-like:focus {
            outline: none;
            border-color: #9d4edd;
            box-shadow: 0 0 0 3px rgba(157, 78, 221, 0.3);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 0.95em;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 0.8em;
        }

        .btn-sm i {
            margin-right: 5px;
        }


        .btn-primary {
            background-color: #9d4edd;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #823db0;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #2a2a45;
            color: #c0c0c0;
            border: 1px solid #3a3a55;
        }

        .btn-secondary:hover {
            background-color: #3a3a55;
            color: #ffffff;
        }

        /* Seção de Pesquisa TMDB */
        .tmdb-search-section {
            background-color: #161625;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .tmdb-search-section h3 {
            margin-bottom: 15px;
            color: #e0e0e0;
            font-size: 1.3em;
        }

        .tmdb-search-input-group {
            display: flex;
            margin-bottom: 20px;
            gap: 10px;
            align-items: center;
        }

        .tmdb-search-select {
            width: auto;
            min-width: 90px;
            flex-shrink: 0;
            padding: 9px;
        }

        .tmdb-search-input-group input[type="text"] {
            flex-grow: 1;
        }

        .tmdb-results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .tmdb-result-item {
            background-color: #1f1f30;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            transition: transform 0.2s ease;
            display: flex;
            flex-direction: column;
        }

        .tmdb-result-item:hover {
            transform: scale(1.05);
        }

        .tmdb-result-item img {
            width: 100%;
            max-width: 180px;
            height: auto;
            border-radius: 6px;
            margin-bottom: 10px;
            object-fit: cover;
            align-self: center;
        }

        .tmdb-result-item h4 {
            font-size: 0.9em;
            color: #c0c0c0;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-grow: 1;
        }

        .tmdb-result-item p {
            font-size: 0.8em;
            color: #888;
            margin-bottom: 10px;
        }

        .btn-import {
            background-color: #28a745;
            color: white;
            width: 100%;
        }

        .btn-import:hover {
            background-color: #218838;
        }


        /* Modal Simples */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0s linear 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
            transition: opacity 0.3s ease;
        }

        .modal-content {
            background-color: #1f1f30;
            padding: 30px;
            border-radius: 8px;
            color: #e0e0e0;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            position: relative;
            min-width: 320px;
            max-width: 90%;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.8em;
            color: #777;
            cursor: pointer;
            background: none;
            border: none;
        }

        .modal-close-btn:hover {
            color: #fff;
        }

        #simpleModalText {
            font-size: 1.1em;
            margin-bottom: 20px;
        }

        .modal-actions .btn {
            margin-top: 10px;
        }


        /* Responsividade */
        @media (max-width: 992px) {
            /* Desktop com sidebar potencialmente escondida - JS cuida do layout */
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                transform: translateY(-100vh) translateX(0);
                left: 0;
                width: 100%;
                height: calc(100vh - 60px);
                top: 60px;
                padding-top: 10px;
                transition: transform 0.3s ease-in-out;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .sidebar.active {
                transform: translateY(0) translateX(0);
            }

            .sidebar-header {
                display: none;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
                padding-top: 75px;
            }

            .main-header {
                left: 0;
                width: 100%;
                padding: 0 15px;
                height: 60px;
            }

            .main-header h1 {
                font-size: 1.4em;
            }

            .cards-container {
                grid-template-columns: 1fr;
            }

            .charts-section {
                grid-template-columns: 1fr;
            }

            .chart-container {
                height: 280px;
                /* Altura ajustada para mobile */
            }

            th,
            td {
                font-size: 0.85em;
            }

            .table-section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .table-section-header .btn {
                align-self: flex-start;
            }

            .tmdb-results-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding-top: 70px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-header i {
                margin-bottom: 10px;
            }

            .card-content h3 {
                font-size: 1em;
            }

            .card-content p {
                font-size: 1.3em;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .btn:last-child {
                margin-bottom: 0;
            }

            .form-group .btn+.btn {
                margin-left: 0 !important;
                margin-top: 10px;
            }

            .form-group .btn-secondary {
                width: 100%;
            }

            .main-header {
                flex-wrap: wrap;
                gap: 10px;
                height: auto;
                min-height: 60px;
            }

            .main-header h1 {
                width: 100%;
                text-align: center;
                margin-bottom: 5px;
            }

            .main-header .menu-toggle {
                order: -1;
            }

            .main-header div:not(:has(.menu-toggle)) {
                margin-left: auto;
            }

            .tmdb-search-input-group {
                flex-direction: column;
            }

            .tmdb-search-select,
            .tmdb-search-input-group input[type="text"],
            .tmdb-search-input-group .btn {
                width: 100%;
            }

            .tmdb-results-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }

            .tmdb-result-item h4 {
                font-size: 0.85em;
            }

            .modal-content {
                min-width: 90%;
                padding: 20px;
            }

            .charts-section {
                /* Em telas muito pequenas, força uma única coluna para os gráficos */
                grid-template-columns: 1fr;
            }

            .chart-container {
                height: 220px;
                /* Altura ainda menor para gráficos em telas muito pequenas */
                padding: 10px;
            }

            .chart-container h3 {
                font-size: 1em;
                margin-bottom: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Admin<span style="color: #c0c0c0;">Panel</span></h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}" class="active">
                            <span class="link-content"><i class="fas fa-tachometer-alt"></i> Dashboard</span>
                        </a>
                    </li>

                    <li class="has-submenu">
                        <a href="#">
                            <span class="link-content"><i class="fas fa-users"></i> Usuários</span>
                            <i class="fas fa-chevron-down submenu-indicator"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('users.index') }}">
                                    <span class="link-content"><i class="fas fa-list-ul"></i> Listar Usuários</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('users.create') }}">
                                    <span class="link-content"><i class="fas fa-user-plus"></i> Adicionar Usuário</span>
                                </a>
                            </li>
                            <li>
                                {{-- <a href="{{ route('roles.index') }}">
                                    <span class="link-content"><i class="fas fa-user-cog"></i> Funções</span>
                                </a> --}}
                            </li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#">
                            <span class="link-content"><i class="fas fa-box-open"></i> Produtos</span>
                            <i class="fas fa-chevron-down submenu-indicator"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('movies.index') }}">
                                    <span class="link-content"><i class="fas fa-film"></i> Filmes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('movies.create') }}">
                                    <span class="link-content"><i class="fas fa-plus-square"></i> Criar Filme</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('series.index') }}">
                                    <span class="link-content"><i class="fas fa-tv"></i> Séries</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tmdb.index') }}">
                                    <span class="link-content"><i class="fas fa-database"></i> TMDB</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sliders.index') }}">
                                    <span class="link-content"><i class="fas fa-images"></i> Sliders</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('channels.index') }}">
                                    <span class="link-content"><i class="fas fa-tv"></i> Canais</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @auth
                        <li>
                            <a href="{{ route('auth.destroy') }}">
                                <span class="link-content"><i class="fas fa-sign-out-alt"></i> Logout</span>
                            </a>
                        </li>
                    @endauth

                    @guest
                        <li>
                            <a href="{{ route('login') }}">
                                <span class="link-content"><i class="fas fa-sign-in-alt"></i> Login</span>
                            </a>
                        </li>
                    @endguest
                </ul>
            </nav>

        </aside>

        <main class="main-content" id="mainContent">
            <header class="main-header" id="mainHeader">
                <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
                <h1>Dashboard</h1>
                <div>
                    <i class="fas fa-bell"
                        style="margin-right: 15px; font-size: 1.2em; color: #9d4edd; cursor: pointer;"></i>
                    <i class="fas fa-user-circle" style="font-size: 1.5em; color: #9d4edd; cursor: pointer;"></i>
                </div>
            </header>
            @yield('content')
            <footer
                style="text-align: center; padding: 20px 0; color: #666; font-size: 0.9em; border-top: 1px solid #2a2a45; margin-top: 30px;">
                &copy; 2025 Admin Panel do Jão. Todos os direitos reservados.
            </footer>

        </main>
    </div>

    <div id="simpleModal" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close-btn" id="modalCloseBtn">&times;</button>
            <p id="simpleModalText">Mensagem do modal aqui.</p>
            <div class="modal-actions">
            </div>
        </div>
    </div>


    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainHeader = document.getElementById('mainHeader');
        const mainContent = document.getElementById('mainContent');
        const menuIcon = menuToggle ? menuToggle.querySelector('i') : null;

        const sidebarWidth = 260;
        const headerHeight = 60; // Altura do header definida no CSS

        function adjustLayoutForSidebar() {
            if (window.innerWidth > 768) { // Desktop
                sidebar.classList.remove('active');
                if (sidebar.classList.contains('desktop-hidden')) {
                    mainHeader.style.left = '0px';
                    mainHeader.style.width = '100%';
                    mainContent.style.marginLeft = '0px';
                    mainContent.style.width = '100%';
                    sidebar.style.transform = 'translateX(-100%)';
                } else {
                    mainHeader.style.left = sidebarWidth + 'px';
                    mainHeader.style.width = `calc(100% - ${sidebarWidth}px)`;
                    mainContent.style.marginLeft = sidebarWidth + 'px';
                    mainContent.style.width = `calc(100% - ${sidebarWidth}px)`;
                    sidebar.style.transform = 'translateX(0)';
                    sidebar.style.width = sidebarWidth + 'px';
                }
                if (menuIcon) {
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                }
                sidebar.style.top = '0';
                sidebar.style.height = '100vh';


            } else { // Mobile
                sidebar.classList.remove('desktop-hidden');
                mainHeader.style.left = '0px';
                mainHeader.style.width = '100%';
                mainContent.style.marginLeft = '0px';
                mainContent.style.width = '100%';
                sidebar.style.width = '100%';

                if (sidebar.classList.contains('active')) {
                    sidebar.style.transform = 'translateY(0)';
                    sidebar.style.top = headerHeight + 'px';
                    sidebar.style.height = `calc(100vh - ${headerHeight}px)`;
                    if (menuIcon) {
                        menuIcon.classList.remove('fa-bars');
                        menuIcon.classList.add('fa-times');
                    }
                } else {
                    sidebar.style.transform = 'translateY(-100vh)';
                    if (menuIcon) {
                        menuIcon.classList.remove('fa-times');
                        menuIcon.classList.add('fa-bars');
                    }
                }
            }
        }


        if (menuToggle && sidebar && menuIcon && mainHeader && mainContent) {
            menuToggle.addEventListener('click', () => {
                if (window.innerWidth > 768) {
                    sidebar.classList.toggle('desktop-hidden');
                } else {
                    sidebar.classList.toggle('active');
                }
                adjustLayoutForSidebar();
            });
        }

        window.addEventListener('resize', adjustLayoutForSidebar);
        document.addEventListener('DOMContentLoaded', () => {
            adjustLayoutForSidebar();

            const salesBarCtx = document.getElementById('salesBarChart');
            if (salesBarCtx) {
                new Chart(salesBarCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                        datasets: [{
                            label: 'Vendas Mensais (R$)',
                            data: [1200, 1900, 3000, 5000, 2300, 3200],
                            backgroundColor: '#9d4edd80',
                            borderColor: '#9d4edd',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, ticks: { color: '#c0c0c0' }, grid: { color: '#2a2a45' } },
                            x: { ticks: { color: '#c0c0c0' }, grid: { color: '#2a2a45ea' } }
                        },
                        plugins: { legend: { labels: { color: '#e0e0e0' } } }
                    }
                });
            }

            const userGrowthCtx = document.getElementById('userGrowthLineChart');
            if (userGrowthCtx) {
                new Chart(userGrowthCtx, {
                    type: 'line',
                    data: {
                        labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5'],
                        datasets: [{
                            label: 'Novos Usuários',
                            data: [30, 45, 60, 55, 75],
                            fill: false,
                            borderColor: '#4CAF50',
                            tension: 0.1,
                            backgroundColor: '#4CAF5030'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: false, ticks: { color: '#c0c0c0' }, grid: { color: '#2a2a45' } },
                            x: { ticks: { color: '#c0c0c0' }, grid: { color: '#2a2a45ea' } }
                        },
                        plugins: { legend: { labels: { color: '#e0e0e0' } } }
                    }
                });
            }
        });


        const navLinks = document.querySelectorAll('.sidebar-nav li > a');
        const submenuParentItems = document.querySelectorAll('.sidebar-nav li.has-submenu > a');

        submenuParentItems.forEach(item => {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                const parentLi = this.parentElement;

                if (!parentLi.classList.contains('submenu-active')) {
                    document.querySelectorAll('.sidebar-nav li.has-submenu.submenu-active').forEach(openSubmenuLi => {
                        if (openSubmenuLi !== parentLi) {
                            openSubmenuLi.classList.remove('submenu-active');
                        }
                    });
                }
                parentLi.classList.toggle('submenu-active');
            });
        });

        navLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                if (!this.parentElement.classList.contains('has-submenu') || this.closest('.submenu')) {
                    navLinks.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');

                    if (this.closest('.submenu')) {
                        const mainParentAnchor = this.closest('li.has-submenu').querySelector('a');
                        if (mainParentAnchor) {
                            navLinks.forEach(nav => nav.classList.remove('active'));
                            mainParentAnchor.classList.add('active');
                            this.classList.add('active');
                        }
                    }
                }

                if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                    if (!this.parentElement.classList.contains('has-submenu') || this.parentElement.classList.contains('submenu-active') || this.closest('.submenu')) {
                        if (!this.parentElement.classList.contains('has-submenu') || this.closest('.submenu')) {
                            sidebar.classList.remove('active');
                            if (menuIcon) {
                                menuIcon.classList.remove('fa-times');
                                menuIcon.classList.add('fa-bars');
                            }
                            adjustLayoutForSidebar();
                        }
                    }
                }
            });
        });


        const simpleModal = document.getElementById('simpleModal');
        const simpleModalText = document.getElementById('simpleModalText');
        const modalCloseBtn = document.getElementById('modalCloseBtn');

        function showModal(message) {
            if (simpleModal && simpleModalText) {
                simpleModalText.textContent = message;
                simpleModal.classList.add('active');
            }
        }

        function closeModal() {
            if (simpleModal) {
                simpleModal.classList.remove('active');
            }
        }

        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', closeModal);
        }
        if (simpleModal) {
            simpleModal.addEventListener('click', (event) => {
                if (event.target === simpleModal) {
                    closeModal();
                }
            });
        }


        const tmdbSearchBtn = document.getElementById('tmdbSearchBtn');
        const tmdbQueryInput = document.getElementById('tmdbQuery');
        const tmdbResultsContainer = document.getElementById('tmdbResultsContainer');
        const tmdbSearchType = document.getElementById('tmdbSearchType');

        if (tmdbSearchBtn && tmdbQueryInput && tmdbResultsContainer && tmdbSearchType) {
            tmdbSearchBtn.addEventListener('click', () => {
                const query = tmdbQueryInput.value;
                const type = tmdbSearchType.value;

                if (query.trim() === "") {
                    showModal("Por favor, digite o nome de um filme ou série para pesquisar.");
                    tmdbResultsContainer.innerHTML = '<p style="color: #888; text-align:center;">Digite algo para pesquisar.</p>';
                    return;
                }

                tmdbResultsContainer.innerHTML = `<p style="color: #9d4edd; text-align:center; padding: 20px 0;">Carregando ${type === 'all' ? 'resultados' : (type === 'movie' ? 'filmes' : 'séries')}...</p>`;

                setTimeout(() => {
                    tmdbResultsContainer.innerHTML = '';
                    for (let i = 1; i <= 6; i++) {
                        const itemType = (type === 'all') ? (i % 2 === 0 ? 'Série' : 'Filme') : (type === 'movie' ? 'Filme' : 'Série');
                        const movieTitle = `${query} - ${itemType} Exemplo ${i}`;
                        const movieYear = 2020 + i;

                        const item = document.createElement('div');
                        item.classList.add('tmdb-result-item');

                        const img = document.createElement('img');
                        img.src = `https://placehold.co/150x225/7852A9/FFF?text=${encodeURIComponent(itemType)}+${i}`;
                        img.alt = `Poster de ${movieTitle}`;

                        const titleH4 = document.createElement('h4');
                        titleH4.textContent = movieTitle;

                        const yearP = document.createElement('p');
                        yearP.textContent = movieYear;

                        const importButton = document.createElement('button');
                        importButton.classList.add('btn', 'btn-sm', 'btn-import');
                        importButton.innerHTML = '<i class="fas fa-download"></i> Importar';
                        importButton.onclick = function () {
                            showModal(`Item "${movieTitle} (${movieYear})" selecionado para importação!`);
                            console.log('Importando:', movieTitle, movieYear, itemType);
                        };

                        item.appendChild(img);
                        item.appendChild(titleH4);
                        item.appendChild(yearP);
                        item.appendChild(importButton);
                        tmdbResultsContainer.appendChild(item);
                    }
                }, 1500);
            });
        }

        document.querySelectorAll('.form-group input:not([type="submit"]):not([type="reset"]):not([type="button"]), .form-group select, .form-group textarea').forEach(el => {
            el.classList.add('form-control-like');
        });
    </script>
</body>

</html>