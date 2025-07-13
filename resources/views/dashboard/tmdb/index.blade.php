@extends('layouts.admin')

@section('title', 'TMDB Search')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-purple-400 mb-6">Buscar no TMDB</h1>
        <x-alert />

        {{-- NOVO FORMULÁRIO DE PESQUISA (com lógica Blade) --}}
        <section class="tmdb-search-section">
            <h3>Pesquisar Filmes/Séries (TMDB)</h3>
            <form method="GET" action="{{ route('tmdb.search') }}">
                <div class="tmdb-search-input-group">
                    <select name="type" class="form-control-like tmdb-search-select">
                        <option value="multi" {{ request('type') === 'multi' ? 'selected' : '' }}>Todos</option>
                        <option value="movie" {{ request('type') === 'movie' ? 'selected' : '' }}>Filme</option>
                        <option value="tv" {{ request('type') === 'tv' ? 'selected' : '' }}>Série</option>
                    </select>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control-like"
                        placeholder="Digite o nome...">

                    <select name="genre" class="form-control-like tmdb-search-select">
                        <option value="">Todos os Gêneros</option>
                        <option value="28" {{ request('genre') == 28 ? 'selected' : '' }}>Ação</option>
                        <option value="12" {{ request('genre') == 12 ? 'selected' : '' }}>Aventura</option>
                        <option value="16" {{ request('genre') == 16 ? 'selected' : '' }}>Animação</option>
                        <option value="35" {{ request('genre') == 35 ? 'selected' : '' }}>Comédia</option>
                        <option value="80" {{ request('genre') == 80 ? 'selected' : '' }}>Crime</option>
                        <option value="99" {{ request('genre') == 99 ? 'selected' : '' }}>Documentário</option>
                        <option value="18" {{ request('genre') == 18 ? 'selected' : '' }}>Drama</option>
                        <option value="10751" {{ request('genre') == 10751 ? 'selected' : '' }}>Família</option>
                        <option value="14" {{ request('genre') == 14 ? 'selected' : '' }}>Fantasia</option>
                        <option value="36" {{ request('genre') == 36 ? 'selected' : '' }}>História</option>
                        <option value="27" {{ request('genre') == 27 ? 'selected' : '' }}>Terror</option>
                        <option value="10402" {{ request('genre') == 10402 ? 'selected' : '' }}>Música</option>
                        <option value="9648" {{ request('genre') == 9648 ? 'selected' : '' }}>Mistério</option>
                        <option value="10749" {{ request('genre') == 10749 ? 'selected' : '' }}>Romance</option>
                        <option value="878" {{ request('genre') == 878 ? 'selected' : '' }}>Ficção Científica</option>
                        <option value="10770" {{ request('genre') == 10770 ? 'selected' : '' }}>Cinema TV</option>
                        <option value="53" {{ request('genre') == 53 ? 'selected' : '' }}>Thriller</option>
                        <option value="10752" {{ request('genre') == 10752 ? 'selected' : '' }}>Guerra</option>
                        <option value="37" {{ request('genre') == 37 ? 'selected' : '' }}>Faroeste</option>
                    </select>
                    <select name="year" class="form-control-like tmdb-search-select">
                        <option value="">Todos os Anos</option>
                        @for ($i = 2025; $i >= 1960; $i--)
                            <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Pesquisar</button>
                </div>
            </form>
        </section>

        {{-- RESULTADOS --}}
        @if(isset($results))
            @if(count($results) === 0)
                <p class="text-red-400">Nenhum resultado encontrado para "{{ $query }}"</p>
            @else
                <div class="tmdb-results-grid mt-4">
                    @foreach($results as $item)
                        @php
                            if (request('type') === 'multi' && isset($item['media_type']) && $item['media_type'] === 'person') {
                                continue;
                            }

                            $image = isset($item['poster_path']) && $item['poster_path']
                                ? 'https://image.tmdb.org/t/p/w500' . $item['poster_path']
                                : 'https://via.placeholder.com/150x225?text=Sem+Imagem';

                            $title = $item['title'] ?? $item['name'] ?? 'Sem título';
                            $typeLabel = $item['media_type'] ?? request('type');
                            $year = $item['release_date'] ?? $item['first_air_date'] ?? 'Sem data';
                            $year = substr($year, 0, 4);
                        @endphp

                        <div class="tmdb-result-item">
                            <img src="{{ $image }}" alt="Poster de {{ $title }}">
                            <h4>{{ $title }}</h4>
                            <p>{{ $year }}</p>
                            <form action="{{ $typeLabel === 'tv' ? route('tmdb.import.series') : route('tmdb.import.movie') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="tmdb_id" value="{{ $item['id'] }}">
                                <button class="btn btn-sm btn-import-ajax" data-id="{{ $item['id'] }}" data-type="{{ $typeLabel }}">
                                    <i class="fas fa-download"></i> Importar
                                </button>

                            </form>
                        </div>
                    @endforeach
                </div>
                @if(isset($results) && count($results) > 0)
                    <div class="tmdb-results-grid mt-4" id="results-container">
                        {{-- seus cards aqui --}}
                    </div>

                    @if($page < $total_pages)
                        <div class="text-center mt-4">
                            <button id="load-more" class="btn btn-primary" data-page="{{ $page + 1 }}">Carregar Mais</button>
                        </div>
                    @endif
                @endif

            @endif
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Função para adicionar o listener do botão Import
            function attachImportHandler(button) {
                if (button.dataset.listenerAdded) return;

                button.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const tmdbId = button.dataset.id;
                    const type = button.dataset.type;
                    const route = type === 'tv'
                        ? "{{ route('tmdb.import.series') }}"
                        : "{{ route('tmdb.import.movie') }}";

                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importando...';

                    try {
                        const response = await fetch(route, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ tmdb_id: tmdbId })
                        });

                        const result = await response.json();

                        if (response.ok) {
                            button.innerHTML = '<i class="fas fa-check"></i> Importado';
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-success');
                        } else {
                            throw new Error(result.message || 'Erro ao importar');
                        }
                    } catch (err) {
                        console.error(err);
                        button.innerHTML = '<i class="fas fa-times"></i> Erro';
                        button.classList.remove('btn-primary');
                        button.classList.add('btn-danger');
                    }
                });

                button.dataset.listenerAdded = "true";
            }

            // Atachar listeners para todos os botões já renderizados
            document.querySelectorAll('.btn-import-ajax').forEach(attachImportHandler);

            // Botão Carregar Mais
            const loadMoreBtn = document.getElementById('load-more');
            if (!loadMoreBtn) return;

            loadMoreBtn.addEventListener('click', async () => {
                const button = loadMoreBtn;
                const nextPage = button.dataset.page;

                button.disabled = true;
                button.textContent = 'Carregando...';

                const params = new URLSearchParams(window.location.search);
                params.set('page', nextPage);

                try {
                    const response = await fetch(window.location.pathname + '?' + params.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    const data = await response.json();

                    if (data.error) throw new Error(data.error);

                    const container = document.getElementById('results-container');

                    data.results.forEach(item => {
                        if (item.media_type === 'person') return; // Ignorar se multi

                        const mediaType = item.media_type || params.get('type') || 'movie';

                        const image = item.poster_path
                            ? 'https://image.tmdb.org/t/p/w500' + item.poster_path
                            : 'https://via.placeholder.com/150x225?text=Sem+Imagem';

                        const title = item.title || item.name || 'Sem título';
                        const yearRaw = item.release_date || item.first_air_date || '';
                        const year = yearRaw ? yearRaw.substr(0, 4) : 'Sem data';

                        const importRoute = mediaType === 'tv'
                            ? "{{ route('tmdb.import.series') }}"
                            : "{{ route('tmdb.import.movie') }}";

                        const card = document.createElement('div');
                        card.classList.add('tmdb-result-item');
                        card.innerHTML = `
                        <img src="${image}" alt="Poster de ${title}">
                        <h4>${title}</h4>
                        <p>${year}</p>
                        <form method="POST" action="${importRoute}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="tmdb_id" value="${item.id}">
                            <button type="button" class="btn btn-sm btn-import-ajax" data-id="${item.id}" data-type="${mediaType}">
                                <i class="fas fa-download"></i> Importar
                            </button>
                        </form>
                    `;
                        container.appendChild(card);

                        // Atacha o listener do import para o novo botão
                        attachImportHandler(card.querySelector('.btn-import-ajax'));
                    });

                    if (data.page >= data.total_pages) {
                        button.remove();
                    } else {
                        button.dataset.page = parseInt(data.page) + 1;
                        button.disabled = false;
                        button.textContent = 'Carregar Mais';
                    }

                } catch (error) {
                    console.error(error);
                    button.disabled = false;
                    button.textContent = 'Carregar Mais';
                    alert('Erro ao carregar mais resultados.');
                }
            });
        });
    </script>

@endsection