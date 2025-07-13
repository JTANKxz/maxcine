@extends('layouts.admin')

@section('content')

    <section class="table-container">
        <div class="table-section-header">
            <h3>Usuários Recentes</h3>
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Criar Novo</a>
        </div>

        <x-alert />

        <div style="max-height: 300px; overflow-y: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Registro</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <span class="status {{ $user->status ?? 'active' }}">
                                    {{ ucfirst($user->status ?? 'Ativo') }}
                                </span>
                            </td>
                            <td>
                                {{-- Ver usuário --}}
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-primary btn-sm" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Editar usuário --}}
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- Excluir usuário --}}
                                <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $user->id }})"
                                        class="btn btn-secondary btn-sm" title="Excluir"
                                        style="background-color: #dc354530; color: #dc3545; border-color: #dc354580; margin-left:5px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="color: red;">Nenhum usuário encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginação --}}
        @if ($users->hasPages())
            <div class="pagination-container" style="margin-top: 20px;">
                <nav>
                    <ul class="pagination">
                        {{-- Anterior --}}
                        @if ($users->onFirstPage())
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Páginas --}}
                        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if ($page == $users->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach

                        {{-- Próxima --}}
                        @if ($users->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif

    </section>

    <script>
        function confirmDelete(userId) {
            if (confirm('Tem certeza que deseja excluir este usuário?')) {
                document.getElementById('delete-form-' + userId).submit();
            }
        }
    </script>

@endsection