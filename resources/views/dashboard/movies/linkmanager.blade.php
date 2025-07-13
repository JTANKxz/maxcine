@extends('layouts.admin')

@section('title', 'Link Manager')

@section('content')

<section class="table-container">
    <div class="table-section-header">
        <h3>Links do Filme</h3>
        <a href="{{ route('movies.links.create', ['movie' => $movie->id]) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Adicionar Link
        </a>
    </div>

    <x-alert />

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Qualidade</th>
                <th>Ordem</th>
                <th>URL</th>
                <th>Tipo</th>
                <th>Assinatura</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($links as $link)
                <tr>
                    <td>{{ $link->name }}</td>
                    <td>{{ $link->quality }}</td>
                    <td>{{ $link->order }}</td>
                    <td class="break-all">{{ $link->url }}</td>
                    <td>{{ $link->type }}</td>
                    <td>{{ $link->player_sub }}</td>
                    <td>
                        <a href="{{ route('movies.links.edit', ['link' => $link->id]) }}" 
                           class="btn btn-primary btn-sm" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('movies.links.destroy', ['link' => $link->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-secondary btn-sm" 
                                    title="Excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir?')"
                                    style="background-color: #dc354530; color: #dc3545; border-color: #dc354580; margin-left:5px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-red-500">Nenhum link encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

@endsection