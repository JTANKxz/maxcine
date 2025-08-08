@extends('layouts.admin')

@section('title', 'Editar Link')

@section('content')

<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-white mb-6">Editar Link de Player</h1>
    <x-alert />
    
    <form action="{{ route('episodes.links.update', ['link' => $link->id]) }}" method="POST" class="bg-gray-800 p-6 rounded shadow text-white">
        @csrf
        @method('PUT')

        {{-- <input type="hidden" name="movie_id" value="{{ $link->movie_id }}"> --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block font-semibold mb-1">Nome do Player</label>
                <input type="text" name="name" id="name" value="{{ $link->name }}" class="form-control-like" required>
            </div>

            <div>
                <label for="quality" class="block font-semibold mb-1">Qualidade</label>
                <input type="text" name="quality" id="quality" value="{{ $link->quality }}" class="form-control-like" required>
            </div>

            <div>
                <label for="order" class="block font-semibold mb-1">Ordem</label>
                <input type="number" name="order" id="order" value="{{ $link->order }}" class="form-control-like" required>
            </div>

            <div>
                <label for="url" class="block font-semibold mb-1">URL</label>
                <input type="url" name="url" id="url" value="{{ $link->url }}" class="form-control-like" required>
            </div>

            <div>
                <label for="type" class="block font-semibold mb-1">Tipo</label>
                <select name="type" id="type" class="form-control-like" required>
                    @foreach (['mp4', 'mkv', 'doodstream', 'streamtape', 'vidhide', 'streamwish'] as $option)
                        <option value="{{ $option }}" @selected($link->type === $option)>{{ strtoupper($option) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="player_sub" class="block font-semibold mb-1">Tipo de Assinatura</label>
                <select name="player_sub" id="player_sub" class="form-control-like" required>
                    <option value="free" @selected($link->player_sub === 'free')>Free</option>
                    <option value="premium" @selected($link->player_sub === 'premium')>Premium</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Salvar
            </button>
        </div>
    </form>
</div>

@endsection