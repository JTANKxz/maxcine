@extends('layouts.admin')

@section('title', 'Adicionar Link')

@section('content')

<section class="form-section container mx-auto py-8 max-w-3xl">
    <h3>Adicionar Novo Link de Player</h3>
    
    <x-alert />

    <form action="{{ route('movies.links.store', ['movie' => $movie->id]) }}" method="POST" class="bg-gray-800 p-6 rounded shadow text-white">
        @csrf

        {{-- movie_id hidden --}}
        <input type="hidden" name="movie_id" value="{{ $movie->id }}">

        <div class="form-group">
            <label for="name">Nome do Player</label>
            <input type="text" name="name" id="name" class="form-control-like" required>
        </div>

        <div class="form-group">
            <label for="quality">Qualidade</label>
            <input type="text" name="quality" id="quality" class="form-control-like" required>
        </div>

        <div class="form-group">
            <label for="order">Ordem</label>
            <input type="number" name="order" id="order" class="form-control-like" required>
        </div>

        <div class="form-group">
            <label for="url">URL</label>
            <input type="url" name="url" id="url" class="form-control-like" required>
        </div>

        <div class="form-group">
            <label for="type">Tipo</label>
            <select name="type" id="type" class="form-control-like" required>
                <option value="mp4">MP4</option>
                <option value="mkv">MKV</option>
                <option value="doodstream">Doodstream</option>
                <option value="streamtape">Streamtape</option>
                <option value="vidhide">Vidhide</option>
                <option value="streamwish">Streamwish</option>
                <option value="embed">Embed</option>
            </select>
        </div>

        <div class="form-group">
            <label for="player_sub">Tipo de Assinatura</label>
            <select name="player_sub" id="player_sub" class="form-control-like" required>
                <option value="free">Free</option>
                <option value="premium">Premium</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Adicionar Link
        </button>

        <button type="reset" class="btn btn-secondary" style="margin-left: 10px;">
            <i class="fas fa-eraser"></i> Limpar
        </button>
    </form>
</section>

@endsection
