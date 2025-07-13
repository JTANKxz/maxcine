@extends('layouts.admin')

@section('title', 'Criar canal')

@section('content')

    <section class="form-section">
        <h3>Adicionar canal</h3>
        <x-alert />
        <form method="POST" action="{{ route('channels.update', $channel->id) }}" class="form-like">
            @csrf
            @method('PUT')
            <input type="hidden" name="slug" value="{{ old('slug', $channel->slug) }}">
            <div class="form-group">
                <label for="name">Nome do Produto</label>
                <input type="text" name="name" id="name" class="form-control-like" placeholder="Ex: Cartoon Network" value="{{ old('name', $channel->name) }}">
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" class="form-control-like" placeholder="Canal de desenhos">{{ old('description', $channel->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="image_url">url de imagem</label>
                <input type="text" name="image_url" id="image_url" class="form-control-like" placeholder="Ex: htpps://example.com/image.jpg" value="{{ old('image_url', $channel->image_url) }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Atualizar Canal</button>
            <button type="reset" class="btn btn-secondary" style="margin-left: 10px;"><i class="fas fa-eraser"></i>
                Limpar</button>
        </form>
    </section>

@endsection