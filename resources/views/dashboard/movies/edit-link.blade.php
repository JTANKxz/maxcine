@extends('layouts.admin')

@section('title', 'Editar Link')

@section('content')

<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-white mb-6">Editar Link de Player</h1>
    <x-alert />
    
    <form action="{{ route('movies.links.update', ['link' => $link->id]) }}" method="POST" class="bg-gray-800 p-6 rounded shadow text-white">
        @csrf
        @method('PUT')

        <input type="hidden" name="movie_id" value="{{ $link->movie_id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block font-semibold mb-1">Nome do Player</label>
                <input type="text" name="name" id="name" value="{{ $link->name }}" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
            </div>

            <div>
                <label for="quality" class="block font-semibold mb-1">Qualidade</label>
                <input type="text" name="quality" id="quality" value="{{ $link->quality }}" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
            </div>

            <div>
                <label for="order" class="block font-semibold mb-1">Ordem</label>
                <input type="number" name="order" id="order" value="{{ $link->order }}" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
            </div>

            <div>
                <label for="url" class="block font-semibold mb-1">URL</label>
                <input type="url" name="url" id="url" value="{{ $link->url }}" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
            </div>

            <div>
                <label for="type" class="block font-semibold mb-1">Tipo</label>
                <select name="type" id="type" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
                    @foreach (['mp4', 'mkv', 'doodstream', 'streamtape', 'vidhide', 'streamwish'] as $option)
                        <option value="{{ $option }}" @selected($link->type === $option)>{{ strtoupper($option) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="player_sub" class="block font-semibold mb-1">Tipo de Assinatura</label>
                <select name="player_sub" id="player_sub" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
                    <option value="free" @selected($link->player_sub === 'free')>Free</option>
                    <option value="premium" @selected($link->player_sub === 'premium')>Premium</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded shadow">
                Atualizar Link
            </button>
        </div>
    </form>
</div>

@endsection