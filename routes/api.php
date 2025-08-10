<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\MovieApiController;
use App\Http\Controllers\Api\V1\ChannelsApiController;
use App\Http\Controllers\Api\V1\CustomSectionApiController;
use App\Http\Controllers\Api\V1\GenreApiController;
use App\Http\Controllers\Api\V1\HomeApiController;
use App\Http\Controllers\Api\V1\SearchApiController;
use App\Http\Controllers\Api\V1\SerieApiController;
use App\Http\Middleware\VerifyCsrfToken;

// Rota pública de login


Route::get('/v1/teste', [AuthApiController::class, 'teste']);

Route::post('/v1/register', [AuthApiController::class,'register']);
Route::post('/v1/login', [AuthApiController::class,'login']);


Route::get('/v1/home', [HomeApiController::class, 'index']);
Route::get('/v1/movies', [MovieApiController::class, 'index']);
Route::get('/v1/movies/{id}', [MovieApiController::class, 'show']);

Route::get('/v1/series', [SerieApiController::class, 'index']);
Route::get('/v1/series/{id}', [SerieApiController::class, 'show']);

Route::get('/v1/genre/{id}', [GenreApiController::class, 'show']);

Route::get('/v1/section/{id}', [CustomSectionApiController::class, 'show']);

Route::get('/v1/search/{query}', [SearchApiController::class, 'search']);

Route::get('/v1/tv-channels', [ChannelsApiController::class, 'index']); 