<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:santcum');

Route::get('/test', function (Request $request) {
    return [
        ['nome' => 'Nilton',
        'idade' => 24,
        ],
        ['nome'=> 'Leticia',
        'idade' => 21]
    ];
});

Route::post('/test',function(Request $request){
    
    return $request;
    
});
