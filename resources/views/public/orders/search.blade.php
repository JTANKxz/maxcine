@extends('layouts.public')

@section('title', 'Fazer pedido - ' . config('app.name'))

@section('content')
    <div class="container">
        <h1 class="page-title">Fazer um Pedido</h1>

        {{-- Formulário de busca --}}
        <form id="searchForm" class="mb-6" method="POST" onsubmit="return false;">
            @csrf
            @method('POST')
            <div class="form-group">
                <select name="type" id="type">
                    <option value="movie">Filme</option>
                    <option value="tv">Série</option>
                </select>
                <input type="text" name="query" id="query" placeholder="Digite o nome do filme ou série...">
            </div>
        </form>

        {{-- Resultados --}}
        <div id="results">
            <!-- Os resultados da busca serão inseridos aqui via JavaScript -->
        </div>
    </div>

    {{-- Script para busca em tempo real --}}
    <script>
        const queryInput = document.getElementById('query');
        const typeSelect = document.getElementById('type');
        const resultsDiv = document.getElementById('results');

        let searchTimeout;

        // Função para executar a busca
        const performSearch = async () => {
            const query = queryInput.value;
            const type = typeSelect.value;

            if (query.length < 3) {
                resultsDiv.innerHTML = '<p class="message">Digite pelo menos 3 caracteres para buscar.</p>';
                return;
            }
            
            resultsDiv.innerHTML = '<p class="message">Buscando...</p>';

            try {
                // Substitua pela URL da sua rota de busca real
                const response = await fetch(`/orders/live-search?query=${encodeURIComponent(query)}&type=${type}`);
                
                if (!response.ok) {
                    throw new Error('Falha na resposta da rede.');
                }
                
                const results = await response.json();
                
                if (results.length === 0) {
                     resultsDiv.innerHTML = '<p class="message">Nenhum resultado encontrado.</p>';
                     return;
                }

                resultsDiv.innerHTML = results.map(item => {
                    const title = type === 'movie' ? item.title : item.name;
                    const year = (type === 'movie' ? item.release_date : item.first_air_date || '').split('-')[0] || 'N/A';
                    const poster = item.poster_path
                        ? `https://image.tmdb.org/t/p/w342${item.poster_path}`
                        : 'https://placehold.co/342x513/1c1c1c/f5f5f5?text=Sem+Poster'; // Placeholder

                    return `
                        <div class="result-card">
                            <img src="${poster}" alt="${title}" onerror="this.onerror=null;this.src='https://placehold.co/342x513/1c1c1c/f5f5f5?text=Erro';">
                            <div class="info">
                                <h3>${title} (${year})</h3>
                                <form action="{{ route('orders.store') }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"> {{-- Adapte para o seu framework --}}
                                    <input type="hidden" name="type" value="${type}">
                                    <input type="hidden" name="tmdb_id" value="${item.id}">
                                    <input type="hidden" name="title" value="${title}">
                                    <input type="hidden" name="poster_url" value="${poster}">
                                    <input type="hidden" name="year" value="${year}">
                                    <button type="submit" class="btn-pedido">
                                        Fazer Pedido
                                    </button>
                                </form>
                            </div>
                        </div>
                    `;
                }).join('');

            } catch (error) {
                console.error('Erro ao buscar:', error);
                resultsDiv.innerHTML = '<p class="message" style="color: #e53e3e;">Erro ao buscar resultados. Tente novamente mais tarde.</p>';
            }
        };

        // Adiciona um ouvinte de evento com debounce para evitar buscas a cada tecla pressionada
        queryInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 500); // Espera 500ms após o usuário parar de digitar
        });
        
        typeSelect.addEventListener('change', performSearch); // Busca novamente se o tipo (filme/série) mudar
    </script>
@endsection