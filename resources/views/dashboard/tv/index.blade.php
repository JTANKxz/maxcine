@extends('layouts.admin')

@section('title', 'Canais de TV')

@section('content')

    <section class="table-container">
        <div class="table-section-header">
            <h3>Canais de TV</h3>
            <x-alert />
            <a href="{{ route('channels.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add
                canal</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagem</th>
                    <th>Name</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($channels as $channel)
                    <tr>
                        <td>{{ $channel->id }}</td>
                        <td><img src="{{ $channel->image_url }}" alt="{{ $channel->name }}" width="50"></td>
                        <td>{{ $channel->name }}</td>
                        <td>
                            <a href="{{ route('channels.links.index', $channel->id) }}" class="btn btn-sm btn-secondary"
                                title="Gerenciar Links"><i class="fas fa-link"></i></a>
                            <a href="{{ route('channels.edit', $channel->id) }}" class="btn btn-primary btn-sm"
                                title="Editar"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('channels.destroy', $channel->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm" title="Excluir"
                                    style="background-color: #dc354530; color: #dc3545; border-color: #dc354580; margin-left:5px;"><i
                                        class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Nenhum canal encontrado.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </section>
@endsection