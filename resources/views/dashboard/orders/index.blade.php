@extends('layouts.admin')

@section('content')

    <section class="table-container">
        <div class="table-section-header">
            <h3>Pedidos Recentes</h3>
        </div>
        <div style="max-height: 300px; overflow-y: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Capa</th>
                        <th>Nome</th>
                        <th>TMDB</th>
                        <th>Ano</th>
                        <th>Tipo</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td><img src="{{ $order->poster_url }}"></td>
                            <td>{{ $order->title }}</td>
                            <td>{{ $order->tmdb_id }}</td>
                            <td>{{ $order->year }}</td>
                            <td>{{ $order->type }}</td>
                            <td><span class="status {{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection