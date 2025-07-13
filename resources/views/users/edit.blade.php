@extends('layouts.admin')

@section('content')

<div class="main-container">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Editar Usuário</h1>

    <x-alert />
    
    <div class="form-container w-full">
        <form action="{{ route('users.update', ['user' => $user->id]) }}" method="post" class="space-y-4 w-full">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" placeholder="Nome" class="form-input" value="{{ old('name', $user->name)  }}">
            </div>

            <div>
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" placeholder="Email" class="form-input" value="{{ old('email', $user->email)  }}">
            </div>

            <button type="submit" class="submit-btn">Salvar</button>
        </form>
    </div>
</div>

@endsection