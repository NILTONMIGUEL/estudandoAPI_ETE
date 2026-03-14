<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
  //criando a função para login
  //pegando os dados vindo do request ou formularios
    public function login(Request $request){

        //fazendo a validação dos campos
        $request->validate([    
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //checando se no meu banco de dados tem alguém com esse email e salvo na variavel
        $user = User::where('email',$request->email)->first();

        //se não existe usuario ou se a senha não for igual a senha do meu usuario
        if(!$user || !Hash::chack($request->password, $user->password)){
            return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
            ]
            ,Response::HTTP_UNAUTHORIZED);// retornando a resposta de não autorizado
        };

        //criando o token da api para manter o usuario logado
        $token = $user->createToken($request->email)->plainTextToken;
        return response()->json([
            'status' => 'ok',
            'message' => 'login successful',
            'token' => $token, //passando o token como resposta para salvar detnro do banco de dados
        ],
        Response::HTTP_OK); //retornando a resposta de status ok

    }

    //fazendo o logout
    public function logout(Request $request){
        //pego o meu o token do meu usuario atual e deleto ele
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'LogOut SuccessFul',
        ],
        Response::HTTP_OK);
    }

    public function register(Request $request){

        $request->validate([
            'name'=> 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:3|max:7|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
    }
}
