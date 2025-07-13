@extends('layouts.admin')

@section('content')
    <div class="main-container">
        <h1>Listar Séries</h1>

        <x-alert />

        <section class="table-container">
            <div class="form-group mt-4 mb-2">
                <input type="text" id="searchSerie" class="form-control-like w-full" placeholder="Pesquisar por título...">
            </div>

            <div class="table-section-header">
                <h3>Series Cadastradas</h3>
                <a href="{{ route('tmdb.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Criar
                    Nova</a>
            </div>

            @if($series->count())
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Capa</th>
                            <th>Título</th>
                            <th>Ano</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($series as $serie)
                            <tr>
                                <td>{{ $serie->id }}</td>
                                <td><img src="{{ $serie->poster_url }}" alt="{{ $serie->title }}" width="70"></td>
                                <td>{{ $serie->title }}</td>
                                <td>{{ $serie->year }}</td>
                                <td>
                                    <a href="{{ route('series.seasons.index', ['serie' => $serie->id]) }}"
                                        class="btn btn-sm btn-secondary" title="Manager Seasons"><i class="fas fa-link"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert-error">
                    <p class="text-red-500">Nenhuma série encontrada.</p>
                </div>
            @endif

            @if ($series->hasPages())
                <nav>
                    <ul class="pagination">
                        {{-- Página anterior --}}
                        @if ($series->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $series->previousPageUrl() }}">&laquo;</a></li>
                        @endif

                        {{-- Páginas --}}
                        @foreach ($series->getUrlRange(1, $series->lastPage()) as $page => $url)
                            @if ($page == $series->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Próxima página --}}
                        @if ($series->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $series->nextPageUrl() }}">&raquo;</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            @endif
        </section>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#searchSerie').on('input', function () {
            const q = $(this).val();

            if (q.length < 2) return;

            $.ajax({
                url: "{{ route('series.search') }}",
                data: { q },
                success: function (data) {
                    let html = '';

                    if (data.length > 0) {
                        data.forEach(serie => {
                            html += `
                                <tr>
                                    <td>${serie.id}</td>
                                    <td><img src="${serie.poster_url}" width="70"></td>
                                    <td>${serie.title}</td>
                                    <td>${serie.year}</td>
                                    <td>
                                        <a href="/admin/series/${serie.id}/seasons" class="btn btn-sm btn-secondary" title="Manager Seasons"><i class="fas fa-link"></i></a>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="5" class="text-red-500">Nenhuma série encontrada.</td></tr>';
                    }

                    $('tbody').html(html);
                    $('.pagination').hide();
                }
            });
        });
    </script>

@endsection