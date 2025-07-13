@extends('layouts.admin')

@section('title', 'Criar Filme')

@section('content')
<section class="form-section">
    <h3>Adicionar Novo Filme</h3>

    <form action="{{ route('movies.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" id="title" name="title" class="form-control-like" required value="{{ old('title') }}">
        </div>

        <div class="form-group">
            <label for="year">Ano</label>
            <input type="number" id="year" name="year" class="form-control-like" required value="{{ old('year') }}">
        </div>

        <div class="form-group">
            <label for="runtime">Duração (min)</label>
            <input type="number" id="runtime" name="runtime" class="form-control-like" value="{{ old('runtime') }}">
        </div>

        <div class="form-group">
            <label for="rating">Avaliação (0 a 10)</label>
            <input type="number" step="0.1" max="10" id="rating" name="rating" class="form-control-like" value="{{ old('rating') }}">
        </div>

        <div class="form-group">
            <label for="overview">Sinopse</label>
            <textarea id="overview" name="overview" rows="4" class="form-control-like">{{ old('overview') }}</textarea>
        </div>

        <div class="form-group">
            <label for="poster_url">URL do Poster</label>
            <input type="text" id="poster_url" name="poster_url" class="form-control-like" value="{{ old('poster_url') }}">
        </div>

        <div class="form-group">
            <label for="backdrop_url">URL do Backdrop</label>
            <input type="text" id="backdrop_url" name="backdrop_url" class="form-control-like" value="{{ old('backdrop_url') }}">
        </div>

        <div class="form-group">
            <label>Gêneros</label>
            <div class="checkbox-container">
                @foreach ($genres as $genre)
                    <div>
                        <input type="checkbox" id="genre_{{ $genre->id }}" name="genres[]" value="{{ $genre->id }}"
                            {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }}>
                        <label for="genre_{{ $genre->id }}">{{ $genre->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Criar Filme</button>
        <button type="reset" class="btn btn-secondary" style="margin-left: 10px;"><i class="fas fa-eraser"></i> Limpar</button>
    </form>
</section>
@endsection
