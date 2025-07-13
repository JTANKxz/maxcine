@extends('layouts.admin')

@section('title', 'Adicionar Link para Canal')

@section('content')

    <section class="form-section">
        <h3>Adicionar Link para Canal {{ $channel->name }}</h3>
        <x-alert />
        <form method="POST" action="{{ route('channels.links.store', ['channel' => $channel->id]) }}" class="form-like">
            @csrf
            <div class="form-group">
                <label for="name">Nome do Link</label>
                <input type="text" name="name" id="name" class="form-control-like" placeholder="Ex: Player 1" required>
            </div>

            <div class="form-group">
                <label for="url">URL</label>
                <input type="url" name="url" id="url" class="form-control-like"
                    placeholder="Ex: https://link.com/stream.m3u8" required>
            </div>

            <div class="form-group">
                <label for="type">Tipo</label>
                <select name="type" id="type" class="form-control-like" required>
                    <option value="embed">Embed</option>
                    <option value="m3u8">M3U8</option>
                </select>
            </div>

            <div class="form-group">
                <label for="player_sub">Player Sub</label>
                <select name="player_sub" id="player_sub" class="form-control-like" required>
                    <option value="free">Free</option>
                    <option value="premium">Premium</option>
                </select>
            </div>

            <div class="form-group">
                <label for="order">Ordem</label>
                <input type="number" name="order" id="order" class="form-control-like" placeholder="0" value="0">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Link</button>
            <button type="reset" class="btn btn-secondary" style="margin-left: 10px;"><i class="fas fa-eraser"></i>
                Limpar</button>
        </form>
    </section>

@endsection