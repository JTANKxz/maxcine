@extends('layouts.admin')

@section('content')

<div class="main-container">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Criar Usuário</h1>

    <x-alert />
    
    <div class="form-container w-full">
        <form action="{{ route('users.store') }}" method="post" class="space-y-4 w-full">
            @csrf
            
            <div>
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" placeholder="Nome" class="form-input">
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" placeholder="Email" class="form-input">
            </div>

            <div>
                <label for="password" class="form-label">Senha</label>
                <input type="password" name="password" placeholder="Senha" class="form-input">
            </div>

            <button type="submit" class="submit-btn">Cadastrar</button>
        </form>
    </div>
</div>

@endsection