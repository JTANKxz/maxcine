@extends('layouts.public')

@section('title', 'Não encontrado - ' . config('app.name'))

@section('content')
    <div class="full-notfound">
        <div class="container-notfound">
            <div class="notfound-icon">
                <i class="fa-solid fa-clapperboard-play"></i>
            </div>
            <h1>Filme Não Encontrado</h1>
            <p>
                Que pena! O filme que você está procurando não foi encontrado em nosso catálogo.
                Você pode fazer um pedido para que ele seja adicionado ou voltar para o início.
            </p>
            <div class="botoes-container">
                <!-- O ideal é que este botão abra um modal ou leve para uma página de contato/pedidos -->
                <a href="{{ route('orders.search') }}" class="btn btn-primario">
                    <i class="fa-solid fa-paper-plane"></i>
                    Fazer um Pedido
                </a>
                <!-- Este botão deve apontar para a página inicial do seu site -->
                <a href="{{ route('home') }}" class="btn">
                    <i class="fa-solid fa-house"></i>
                    Voltar ao Início
                </a>
            </div>
        </div>
    </div>
@endsection