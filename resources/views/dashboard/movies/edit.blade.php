@extends('layouts.admin')

@section('title', 'Editar Filme')

@section('content')
<section class="form-section">
    <h3>Editar Filme: {{ $movie->title }}</h3>

    <x-alert />

    <form action="{{ route('movies.update', $movie->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" id="title" name="title" class="form-control-like" required value="{{ old('title', $movie->title) }}">
        </div>

        <div class="form-group">
            <label for="year">Ano</label>
            <input type="number" id="year" name="year" class="form-control-like" required value="{{ old('year', $movie->year) }}">
        </div>

        <div class="form-group">
            <label for="runtime">Duração (min)</label>
            <input type="number" id="runtime" name="runtime" class="form-control-like" value="{{ old('runtime', $movie->runtime) }}">
        </div>

        <div class="form-group">
            <label for="rating">Avaliação (0 a 10)</label>
            <input type="number" step="0.1" max="10" id="rating" name="rating" class="form-control-like" value="{{ old('rating', $movie->rating) }}">
        </div>

        <div class="form-group">
            <label for="overview">Sinopse</label>
            <textarea id="overview" name="overview" rows="4" class="form-control-like">{{ old('overview', $movie->overview) }}</textarea>
        </div>

        <div class="form-group">
            <label for="poster_url">URL do Poster</label>
            <input type="text" id="poster_url" name="poster_url" class="form-control-like" value="{{ old('poster_url', $movie->poster_url) }}">
        </div>

        <div class="form-group">
            <label for="backdrop_url">URL do Backdrop</label>
            <input type="text" id="backdrop_url" name="backdrop_url" class="form-control-like" value="{{ old('backdrop_url', $movie->backdrop_url) }}">
        </div>

        <div class="form-group">
            <label>Gêneros</label>
            @foreach ($genres as $genre)
                <div class="checkbox-container">
                    <input type="checkbox" id="genre_{{ $genre->id }}" name="genres[]" value="{{ $genre->id }}"
                        {{ in_array($genre->id, old('genres', $movie->genres->pluck('id')->toArray())) ? 'checked' : '' }}>
                    <label for="genre_{{ $genre->id }}">{{ $genre->name }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Filme</button>
        <button type="reset" class="btn btn-secondary" style="margin-left: 10px;"><i class="fas fa-eraser"></i> Limpar</button>
    </form>
</section>
@endsection
