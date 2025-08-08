@extends('layouts.admin')

@section('content')
    <div class="main-container">
        <h1>Listar Custom Sections</h1>

        <x-alert />

        <section class="table-container">
            <div class="table-section-header">
                <h3>Seções Home</h3>
                <a href="{{ route('sections.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Criar
                    Nova</a>
            </div>

            @if($sections->count())
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $section)
                            <tr>
                                <td>{{ $section->id }}</td>
                                <td>{{ $section->name }}</td>
                                <td>{{ $section->active ? 'Ativo' : 'Inativo' }}</td>
                                <td>
                                    <a href="{{ route('sections.edit', $section->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Editar</a>
                                    <form action="{{ route('sections.destroy', $section->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-secondary"
                                            onclick="return confirm('Tem certeza que deseja excluir esta seção?')"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert-error">
                    <p class="text-red-500">Nenhuma seção encontrada.</p>
                </div>
            @endif
        </section>
    </div>
@endsection