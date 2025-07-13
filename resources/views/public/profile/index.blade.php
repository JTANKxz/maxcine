@extends('layouts.public')

@section('title', "Meu Perfil - " . config('app.name'))

@section('content')
    <div class="main-profile-container">

        <header class="profile-header-section">
            <div class="header-avatar-wrapper">
                <i class="fa-regular fa-user" style="font-size: 70px; color: #ffc107;"></i>

                {{-- <img src="https://placehold.co/150x150/202020/ffc107?text=User" alt="Profile Picture" class="avatar-image"> --}}
            </div>
            <div class="user-details-info">
                <h1 class="user-display-name">
                    {{ Auth::user()->name }}
                </h1>
                <p class="user-display-email">
                    {{ Auth::user()->email }}
                </p>
                {{-- User statistics commented out as per previous requests. --}}
                {{-- <div class="user-stats-container">
                    <div class="stat-item">
                        <span class="stat-number">148</span>
                        <span class="stat-label">My List</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">23</span>
                        <span class="stat-label">Following</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">99</span>
                        <span class="stat-label">Followers</span>
                    </div>
                </div> --}}
            </div>
        </header>

        <main class="profile-main-content">
            <section class="user-list-section">
                <h2 class="section-heading">Minha lista</h2>

                <nav class="filter-navigation-menu">
                    <button class="filter-button current-active" data-target="movie-items-scroll">Filmes</button>
                    <button class="filter-button" data-target="series-items-scroll">Séries</button>
                </nav>

                <div id="movie-items-scroll" class="content-scrollable-container">
                    <div class="content-horizontal-list">
                        @forelse($watchlistMovies as $movie)
                            <div class="list-item-card">
                                <div class="card-image-content-wrapper">
                                    <a href="{{ route('movie.show', $movie->slug) }}" class="card-link-wrapper">
                                        <img src="{{ $movie->poster_url ?? 'https://placehold.co/300x450/101010/333333?text=Poster' }}"
                                            alt="{{ $movie->title }}" class="card-image-display">
                                        <div class="item-overlay-details">
                                            <span class="item-title-text">{{ $movie->title }}</span>
                                            <div class="progress-bar-group">
                                                <div class="progress-bar-base">
                                                    <div class="progress-bar-fill" style="width: {{ $movie->progress ?? 0 }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                {{-- Remove icon, always visible and overlapping --}}
                                <i class="fa-solid fa-circle-xmark remove-item-overlay-icon" data-id="{{ $movie->id }}"
                                    data-type="movie" data-parent=".list-item-card"></i>
                            </div>
                        @empty
                            <p class="empty-list-message">Nenhum filme na sua lista.</p>
                        @endforelse
                    </div>
                </div>

                <div id="series-items-scroll" class="content-scrollable-container" style="display: none;">
                    <div class="content-horizontal-list">
                        @forelse($watchlistSeries as $serie)
                            <div class="list-item-card">
                                <div class="card-image-content-wrapper">
                                    <a href="{{ route('serie.show', $serie->slug) }}" class="card-link-wrapper">
                                        <img src="{{ $serie->poster_url ?? 'https://placehold.co/300x450/101010/333333?text=Poster' }}"
                                            alt="{{ $serie->title }}" class="card-image-display">
                                        <div class="item-overlay-details">
                                            <span class="item-title-text">{{ $serie->title }}</span>
                                            <div class="progress-bar-group">
                                                <div class="progress-bar-base">
                                                    <div class="progress-bar-fill" style="width: {{ $serie->progress ?? 0 }}%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                {{-- Remove icon, always visible and overlapping --}}
                                <i class="fa-solid fa-circle-xmark remove-item-overlay-icon" title="Remover da lista"
                                    data-id="{{ $serie->id }}" data-type="serie" data-parent=".list-item-card"></i>
                            </div>
                        @empty
                            <p class="empty-list-message">Nenhuma série na sua lista.</p>
                        @endforelse
                    </div>
                </div>

            </section>

            <section class="account-settings-section">
                <h2 class="section-heading">Conta e Configurações</h2>
                <ul class="settings-options-list">
                    {{-- <li><a href="#" class="settings-list-link">Editar Perfil</a></li>
                    <li><a href="#" class="settings-list-link">Gerenciar Assinatura</a></li> --}}
                    <li><a href="{{ route('auth.destroy') }}" class="settings-list-link">Sair</a></li>
                </ul>
            </section>

        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Select all buttons and content containers
            const filterButtons = document.querySelectorAll('.filter-button');
            const contentContainers = document.querySelectorAll('.content-scrollable-container');

            // Add click event listener to each button
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove 'current-active' class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('current-active'));
                    // Add 'current-active' class to the clicked button
                    button.classList.add('current-active');

                    // Get the ID of the target from the 'data-target' attribute
                    const targetId = button.getAttribute('data-target');

                    // Hide all content containers
                    contentContainers.forEach(container => {
                        container.style.display = 'none';
                    });

                    // Show only the target container
                    document.getElementById(targetId).style.display = 'block';
                });
            });

            // Ensure the movies tab is visible by default on page load
            document.getElementById('movie-items-scroll').style.display = 'block';
            document.getElementById('series-items-scroll').style.display = 'none';
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.remove-item-overlay-icon').forEach(icon => {
                icon.addEventListener('click', () => {
                    const id = icon.dataset.id;
                    const type = icon.dataset.type;
                    const parentSelector = icon.dataset.parent;
                    const parentElement = icon.closest(parentSelector);

                    if (!id || !type) return;

                    fetch("{{ route('watchlist.toggle') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({
                            id,
                            type
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.removed || !data.added) {
                                parentElement?.remove(); // Remove o card visualmente
                                showToast("Removido da lista");
                            } else {
                                showToast("Erro ao remover");
                            }
                        })
                        .catch(() => showToast("Erro ao remover"));
                });
            });

            function showToast(message) {
                const toast = document.getElementById('toast');
                toast.textContent = message;
                toast.classList.remove('hidden');
                toast.classList.add('show-toast');

                setTimeout(() => {
                    toast.classList.remove('show-toast');
                    toast.classList.add('hidden');
                }, 3000);
            }
        });
    </script>
@endsection