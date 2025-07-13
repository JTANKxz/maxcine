@extends('layouts.public')

@section('title', 'Não encontrado - ' . config('app.name'))

@section('content')

    <div class="full-sucesso">
        <div class="container-sucesso">
            <div class="sucesso-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h1>Pedido Enviado!</h1>
            <p>
                O seu pedido foi recebido e será analisado pela nossa equipe. Caso seja aprovado, seu pedido estará
                disponível em até 24 horas.
            </p>
            <p>
                Agradecemos a sua colaboração para tornar
                nosso catálogo ainda melhor!
            </p>
            <div class="botoes-container">
                <!-- Este botão deve apontar para a página de pedidos -->
                <a href="{{ route('orders.search') }}" class="btn btn-primario">
                    <i class="fa-solid fa-plus"></i>
                    Fazer Novo Pedido
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