@extends('layouts.admin')

@section('title', 'Criar canal')

@section('content')

    <section class="form-section">
        <h3>Adicionar canal</h3>
        <x-alert />
        <form method="POST" action="{{ route('channels.store') }}" class="form-like">
            @csrf
            <input type="hidden" name="slug" value="">
            <div class="form-group">
                <label for="name">Nome do Produto</label>
                <input type="text" name="name" id="name" class="form-control-like" placeholder="Ex: Cartoon Network">
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea id="description" name="description" class="form-control-like" placeholder="Canal de desenhos"></textarea>
            </div>
            <div class="form-group">
                <label for="image_url">url de imagem</label>
                <input type="text" name="image_url" id="image_url" class="form-control-like" placeholder="Ex: htpps://example.com/image.jpg">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar Canal</button>
            <button type="reset" class="btn btn-secondary" style="margin-left: 10px;"><i class="fas fa-eraser"></i>
                Limpar</button>
        </form>
    </section>

@endsection