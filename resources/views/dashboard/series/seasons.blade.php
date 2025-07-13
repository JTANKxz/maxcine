

@extends('layouts.admin')

@section('content')
<div class="main-container">
    <h1>Listar Filmes</h1>

    <x-alert />

    <section class="table-container">
        <div class="table-section-header">
            <h3>Temporadas Cadastradas</h3>
            <form action="{{ route('series.import.seasons', $serie->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary btn-sm">
                    Importar Temporadas Automaticamente
                </button>
            </form>
        </div>

        @if($seasons->count())
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
                @foreach($seasons as $season)
                <tr>
                    <td>{{ $season->id }}</td>
                    <td>{{ $season->season_number }}</td>
                    <td>{{ $season->name }}</td>
                    <td>
                        <a href="{{ route('series.seasons.episodes', ['serie' => $serie->id, 'season' => $season->id]) }}" class="btn btn-sm btn-secondary" title="Manager Seasons"><i class="fas fa-link"></i></a>
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