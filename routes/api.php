<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthApiController;

// Rota pública de login


Route::get('/v1/teste', [AuthApiController::class, 'teste']);

Route::post('/v1/register', [AuthApiController::class,'register']);
Route::post('/v1/login', [AuthApiController::class,'login']);