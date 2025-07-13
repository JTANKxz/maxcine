@extends('layouts.admin')

@section('content')
    <div class="main-container">
        <h1>Listar Sliders</h1>

        <x-alert />

        <section class="table-container">
            <div class="table-section-header">
                <h3>Sliders</h3>
                <a href="{{ route('sliders.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Criar
                    Novo</a>
            </div>

            @if($sliders->count())
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Capa</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Ano</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td>{{ $slider->id }}</td>
                                <td><img src="{{ $slider->backdrop_url }}" alt="{{ $slider->title }}" width="70"></td>
                                <td>{{ $slider->title }}</td>
                                <td>{{ $slider->type }}</td>
                                <td>{{ $slider->year }}</td>
                                <td>
                                    <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-secondary"
                                            onclick="return confirm('Tem certeza que deseja excluir este slider?')"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert-error">
                    <p class="text-red-500">Nenhum slider encontrado.</p>
                </div>
            @endif
        </section>
    </div>
@endsection