<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


//rotas da api crud

Route::Post('/register',[UserController::class, 'register']);
Route::Post('/login',[UserController::class, 'login']);


Route::post('/cliente/register',[ClienteController::class, 'register']);
Route::post('/cliente/login',[ClienteController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    //criando a rota que tem todos os controles
    Route::apiResource('produto',ProdutoController::class); 
    Route::get('/users',[UserController::class, 'index']);
    Route::get('/show{id}',[UserController::class, 'show']);
    Route::put('/update{id}',[UserController::class, 'update']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/validar',[UserController::class, 'validarToken']);

    Route::get('/cliente',[ClienteController::class, 'index']);
    Route::post('/cliente/logout',[ClienteController::class, 'logout']);
    Route::get('/cliente{id}',[ClienteController::class, 'show']);
    Route::put('/cliente/update{id}',[ClienteController::class, 'update']);

});



