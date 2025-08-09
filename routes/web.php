<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EpisodePlayLinkController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MoviePlayLinkController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\TMDBController;
use App\Http\Controllers\TVChannelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//APISUSE
use App\Http\Controllers\Api\V1\MovieApiController;
use App\Http\Controllers\Api\v1\AuthApiController;
use App\Http\Controllers\Api\V1\ChannelsApiController;
use App\Http\Controllers\Api\V1\CustomSectionApiController;
use App\Http\Controllers\Api\V1\GenreApiController;
use App\Http\Controllers\Api\V1\HomeApiController;
use App\Http\Controllers\Api\V1\SearchApiController;
use App\Http\Controllers\Api\V1\SerieApiController;
use App\Http\Controllers\AplicativoController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SectionsController;
use App\Http\Middleware\VerifyCsrfToken;

Route::get('/filme/{id}', [MovieController::class, 'showByTmdb'])->name('movie.by.tmdb');

// PUBLIC ROUTES
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pesquisa', [SearchController::class, 'index'])->name('search.index');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/cadastrar', [AuthController::class, 'register'])->name('register');
Route::post('/register-proccess', [AuthController::class, 'store'])->name('auth.register');
Route::post('/login-proccess', [AuthController::class, 'login'])->name('auth.login');
Route::get('/logout', [AuthController::class, 'destroy'])->name('auth.destroy');

Route::get('/filmes', [MovieController::class, 'index'])->name('movies');
Route::get('/filmes/{slug}', [MovieController::class, 'show'])->name('movie.show');

Route::get('/series', [SerieController::class, 'index'])->name('series');
Route::get('/series/{slug}', [SerieController::class, 'show'])->name('serie.show');
Route::get('/categorias/{slug}', [GenreController::class, 'show'])->name('genres.show');

Route::get('/app/download', [AplicativoController::class, 'index'])->name('download.app');

Route::get('/canais', [TVChannelController::class, 'index'])->name('tv.index');
Route::get('/canal/{slug}', [TVChannelController::class, 'show'])->name('tv.show');

// PROTECTED USER ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/pedidos', [OrderController::class, 'searchForm'])->name('orders.search');
    Route::get('/orders/live-search', [OrderController::class, 'liveSearch'])->name('orders.live.search');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/perfil', [UserController::class, 'profile'])->name('user.profile');

    Route::post('/watchlist/toggle', [\App\Http\Controllers\WatchlistController::class, 'toggleWatchlist'])->name('watchlist.toggle');
    Route::get('/minha-lista', [\App\Http\Controllers\WatchlistController::class, 'index'])->name('watchlist.index');
});

// PROTECTED ADMIN ROUTES
Route::prefix('admin')->middleware('admin')->group(function () {

    // Dashboard✅
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // TV Channels✅
    Route::prefix('channels')->name('channels.')->group(function () {
        Route::get('/', [DashboardController::class, 'tvChannels'])->name('index');
        Route::get('/create', [DashboardController::class, 'createChannel'])->name('create');
        Route::post('/', [DashboardController::class, 'storeChannel'])->name('store');
        Route::get('/{channel}/edit', [DashboardController::class, 'editChannel'])->name('edit');
        Route::put('/{channel}', [DashboardController::class, 'updateChannel'])->name('update');
        Route::delete('/{channel}', [DashboardController::class, 'destroyChannel'])->name('destroy');

        // Channel Links✅
        Route::prefix('{channel}/links')->name('links.')->group(function () {
            Route::get('/', [TVChannelController::class, 'listLinks'])->name('index');
            Route::get('/create', [TVChannelController::class, 'createLink'])->name('create');
            Route::post('/', [TVChannelController::class, 'storeLink'])->name('store');
            Route::get('/{link}/edit', [TVChannelController::class, 'editLink'])->name('edit');
            Route::put('/{link}', [TVChannelController::class, 'updateLink'])->name('update');
            Route::delete('/{link}', [TVChannelController::class, 'destroyLink'])->name('destroy');
        });
    });

    // Users✅
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // TMDB
    Route::prefix('tmdb')->name('tmdb.')->group(function () {
        Route::get('/', [TMDBController::class, 'index'])->name('index');
        Route::get('/search', [TMDBController::class, 'search'])->name('search');
        Route::post('/movie/import', [TMDBController::class, 'import'])->name('import.movie');
        Route::post('/series/import', [TMDBController::class, 'importSerie'])->name('import.series');
    });

    // Movies✅
    Route::prefix('movies')->name('movies.')->group(function () {
        Route::get('/', [DashboardController::class, 'movies'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/', [MovieController::class, 'store'])->name('store');
        Route::get('/{movie}/edit', [MovieController::class, 'edit'])->name('edit');
        Route::put('/{movie}', [MovieController::class, 'update'])->name('update');
        Route::delete('/{movie}', [MovieController::class, 'destroy'])->name('destroy');
        Route::get('/movies/search', [DashboardController::class, 'searchMovies'])->name('search');


        // Movie Links✅
        Route::get('/{movie}/links', [MovieController::class, 'linkManager'])->name('links.index');
        Route::get('/{movie}/links/create', [MoviePlayLinkController::class, 'create'])->name('links.create');
        Route::post('/{movie}/links', [MoviePlayLinkController::class, 'store'])->name('links.store');
        Route::get('/links/{link}/edit', [MoviePlayLinkController::class, 'edit'])->name('links.edit');
        Route::put('/links/{link}', [MoviePlayLinkController::class, 'update'])->name('links.update');
        Route::delete('/links/{link}', [MoviePlayLinkController::class, 'destroy'])->name('links.destroy');
    });

    // Series✅
    Route::prefix('series')->name('series.')->group(function () {
        Route::get('/', [DashboardController::class, 'series'])->name('index');
        Route::delete('/{serie}', [DashboardController::class, 'destroySerie'])->name('destroy');
        Route::get('/{serie}/seasons', [DashboardController::class, 'seasons'])->name('seasons.index');
        Route::get('/{serie}/seasons/{season}/episodes', [DashboardController::class, 'episodes'])->name('seasons.episodes');
        Route::post('/{serie}/import-seasons', [DashboardController::class, 'importSeasons'])->name('import.seasons');
        Route::post('/{serie}/import-episodes', [DashboardController::class, 'importEpisodes'])->name('import.episodes');
        Route::get('/series/search', [DashboardController::class, 'searchSeries'])->name('search');
    });

    // Episodes✅
    Route::prefix('episodes')->name('episodes.')->group(function () {
        Route::get('/{episode}/links', [EpisodePlayLinkController::class, 'index'])->name('links.index');
        Route::get('/{episode}/links/create', [EpisodePlayLinkController::class, 'create'])->name('links.create');
        Route::post('/links/store', [EpisodePlayLinkController::class, 'store'])->name('links.store');
        Route::get('/links/{link}/edit', [EpisodePlayLinkController::class, 'edit'])->name('links.edit');
        Route::put('/links/{link}/update', [EpisodePlayLinkController::class, 'update'])->name('links.update');
        Route::delete('/links/{link}/delete', [EpisodePlayLinkController::class, 'destroy'])->name('links.destroy');
    });

    // Sliders✅
    Route::prefix('sliders')->name('sliders.')->group(function () {
        Route::get('/', [DashboardController::class, 'sliders'])->name('index');
        Route::get('/create', [DashboardController::class, 'slidersCreate'])->name('create');
        Route::post('/', [DashboardController::class, 'slidersStore'])->name('store');
        Route::delete('/{slider}', [DashboardController::class, 'destroySlider'])->name('destroy');
        Route::get('/search', [DashboardController::class, 'search'])->name('search');
    });

    // Orders✅
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [DashboardController::class, 'orders'])->name('index');
    });

    //sections
    Route::prefix('sections')->name('sections.')->group(function () {
        Route::get('/', [SectionsController::class, 'index'])->name('index');
        Route::get('/create', [SectionsController::class, 'create'])->name('create');
        Route::post('/store', [SectionsController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SectionsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SectionsController::class, 'update'])->name('update');
        Route::delete('/{section}', [SectionsController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('app-config')->name('config.')->group(function () {
        Route::get('/', [ConfigController::class, 'index'])->name('index');
        Route::get('/notifications', [ConfigController::class, 'pushNotification'])->name('notifications');
    });

});

//API ROUTES
Route::get('/v1/home', [HomeApiController::class, 'index']);
Route::get('/v1/movies', [MovieApiController::class, 'index']);
Route::get('/v1/movies/{id}', [MovieApiController::class, 'show']);

Route::get('/v1/series', [SerieApiController::class, 'index']);
Route::get('/v1/series/{id}', [SerieApiController::class, 'show']);

Route::get('/v1/genre/{id}', [GenreApiController::class, 'show']);

Route::get('/v1/section/{id}', [CustomSectionApiController::class, 'show']);

Route::get('/v1/search/{query}', [SearchApiController::class, 'search']);

Route::get('/v1/tv-channels', [ChannelsApiController::class, 'index']); 

// Route::middleware([VerifyCsrfToken::class])->group(function () {
//     Route::post('/v1/login', [AuthApiController::class, 'login']);
//     Route::post('/v1/logout', [AuthApiController::class, 'logout']);
// });

// Route::get('/v1/teste', function () {
//     return response()->json(['message' => 'Test route']);
// });