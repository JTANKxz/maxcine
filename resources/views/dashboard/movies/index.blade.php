@extends('layouts.admin')

@section('content')
    <div class="main-container">
        <h1>Listar Filmes</h1>

        <x-alert />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <section class="table-container">
            <div class="table-section-header">
                <h3>Filmes Cadastrados</h3>
                <div class="form-group mt-4 mb-2">
                    <input type="text" id="searchMovie" class="form-control-like w-full"
                        placeholder="Pesquisar por título...">
                </div>

                <a href="{{ route('movies.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Criar
                    Novo</a>
            </div>

            @if($movies->count())
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
                        @foreach($movies as $movie)
                            <tr>
                                <td>{{ $movie->id }}</td>
                                <td><img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" width="70"></td>
                                <td>{{ $movie->title }}</td>
                                <td>{{ $movie->year }}</td>
                                <td>
                                    <a href="{{ route('movies.links.index', ['movie' => $movie->id]) }}"
                                        class="btn btn-sm btn-secondary" title="Links Manager"><i class="fas fa-link"></i></a>

                                    <a href="{{ route('movies.edit', ['movie' => $movie->id]) }}" class="btn btn-sm btn-primary"
                                        title="Editar"><i class="fas fa-edit"></i></a>

                                    <form id="delete-form-{{ $movie->id }}"
                                        action="{{ route('movies.destroy', ['movie' => $movie->id]) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                            onclick="confirmDelete({{ $movie->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert-error">
                    <p class="text-red-500">Nenhum filme encontrado.</p>
                </div>
            @endif

            @if ($movies->hasPages())
                <nav>
                    <ul class="pagination">
                        {{-- Página anterior --}}
                        @if ($movies->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $movies->previousPageUrl() }}">&laquo;</a></li>
                        @endif

                        {{-- Páginas --}}
                        @foreach ($movies->getUrlRange(1, $movies->lastPage()) as $page => $url)
                            @if ($page == $movies->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Próxima página --}}
                        @if ($movies->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $movies->nextPageUrl() }}">&raquo;</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                        @endif
                    </ul>
                </nav>
            @endif
        </section>
    </div>
    <script>
        $('#searchMovie').on('input', function () {
            const q = $(this).val();

            if (q.length < 2) return;

            $.ajax({
                url: "{{ route('movies.search') }}",
                data: { q },
                success: function (data) {
                    let html = '';

                    if (data.length > 0) {
                        data.forEach(movie => {
                            html += `
                                    <tr>
                                        <td>${movie.id}</td>
                                        <td><img src="${movie.poster_url}" width="70"></td>
                                        <td>${movie.title}</td>
                                        <td>${movie.year}</td>
                                        <td>
                                            <a href="/dashboard/movies/${movie.id}/links" class="btn btn-sm btn-secondary"><i class="fas fa-link"></i></a>
                                            <a href="/dashboard/movies/${movie.id}/edit" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                            <form action="/dashboard/movies/${movie.id}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="5" class="text-red-500">Nenhum resultado encontrado.</td></tr>';
                    }

                    $('tbody').html(html);
                    $('.pagination').hide();
                }
            });
        });
    </script>

@endsection