@extends('layouts.admin')

@section('content')
    <div class="main-container">
        <h1>Importar Episódios Automaticamente</h1>

        <x-alert />

        <section class="table-container">
            <div class="table-section-header">
                <h3>Eps Cadastradas</h3>
                <form action="{{ route('series.import.episodes', $serie->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-primary btn-sm">
                        Importar Temporadas Automaticamente
                    </button>
                </form>
            </div>

            @if($episodes->count())
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($episodes as $episode)
                                            <tr>
                                                <td>{{ $episode->id }}</td>
                                                <td>{{ $episode->episode_number }}</td>
                                                <td>{{ $episode->name }}</td>
                                                <td>
                                                    <a href="{{ route('episodes.links.index', ['episode' => $episode->id])
                             }}" class="btn btn-sm btn-secondary" title="Gerenciar links"><i class="fas fa-link"></i></a>
                                                </td>
                                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert-error">
                    <p class="text-red-500">Nenhuma temporada encontrada.</p>
                </div>
            @endif
        </section>
    </div>
@endsection