@extends('layouts.admin')

@section('content')

<div class="main-container">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Editar Usuário</h1>

    <x-alert />
    
    <div class="form-container w-full">
        <div class="user-info">
            <div class="user-details">
                <h2 class="text-lg font-bold">Nome: {{ $user->name }}</h2>
                <p class="text-gray-600">Email: {{ $user->email }}</p>
            </div>

            <div class="actions">
                <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="text-blue-500 hover:text-blue-700">Editar</a>

                <form action="{{ route('users.index', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700">Excluir</button>
                </form>
            </div>
        </div>        
    </div>
</div>

@endsection