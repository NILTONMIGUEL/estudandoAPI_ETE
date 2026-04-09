<?php

use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:santcum');

//rotas da api crud
Route::get('/',[UserController::class, 'index']);
Route::Post('/register',[UserController::class, 'register']);
Route::get('/show{id}',[UserController::class, 'show']);
Route::put('/update{id}',[UserController::class, 'update']);
Route::Post('/login',[UserController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);

//criando a rota que tem todos os controles
Route::apiResource('produto',ProdutoController::class);
