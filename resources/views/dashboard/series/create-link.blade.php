@extends('layouts.admin')

@section('title', 'Adicionar Link de Episódio')

@section('content')

<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-white mb-6">Adicionar Novo Link de Player para Episódio</h1>
    <x-alert />
    <form action="{{ route('episodes.links.store') }}" method="POST" class="bg-gray-800 p-6 rounded shadow text-white">
        @csrf

        {{-- episode_id hidden --}}
        <input type="hidden" name="episode_id" value="{{ $episode->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block font-semibold mb-1">Nome do Player</label>
                <input type="text" name="name" id="name" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
            </div>

            <div>
                <label for="quality" class="block font-semibold mb-1">Qualidade</label>
                <input type="text" name="quality" id="quality" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
            </div>

            <div>
                <label for="order" class="block font-semibold mb-1">Ordem</label>
                <input type="number" name="order" id="order" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
            </div>

            <div>
                <label for="url" class="block font-semibold mb-1">URL</label>
                <input type="url" name="url" id="url" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
            </div>

            <div>
                <label for="type" class="block font-semibold mb-1">Tipo</label>
                <select name="type" id="type" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
                    <option value="mp4">MP4</option>
                    <option value="mkv">MKV</option>
                    <option value="doodstream">Doodstream</option>
                    <option value="streamtape">Streamtape</option>
                    <option value="vidhide">Vidhide</option>
                    <option value="streamwish">Streamwish</option>
                    <option value="embed">Embed</option>
                </select>
            </div>

            <div>
                <label for="player_sub" class="block font-semibold mb-1">Tipo de Assinatura</label>
                <select name="player_sub" id="player_sub" class="w-full p-2 rounded bg-gray-900 border border-gray-600" required>
                    <option value="free">Free</option>
                    <option value="premium">Premium</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded shadow">
                Adicionar Link
            </button>
        </div>
    </form>
</div>

@endsection
