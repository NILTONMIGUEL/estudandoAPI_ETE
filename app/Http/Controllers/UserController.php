<?php

namespace App\Http\Controllers;

use App\Models\User;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //criando o página inicial para retornar todos os usuarios
    public function index(){
        //salvando todos os meus usuarios dentro da variavel users
        $users = User::all();
        //retornando um json com todos os usuarios e a resposta de ok
        return response()->json($users , Response::HTTP_OK);
    }

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
        }

            //criando meu token e salvando na variavel token
            $token = $user->createToken($request->email)->plainTextToken;
            //retornando meu token
            return response()->json([
                'status' => 'Success',
                'message' => 'login successful',
                'token' => $token,
            ], Response::HTTP_OK);
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


        
        
    //criando a função de registrar o usuario
    public function register(Request $request){

        $request->validate([
            'name'=> 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:3|max:7|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //retornando para mim o usuario criado e o status de ok
        return response()->json([
            'status' => 'success',
            'message' => 'register successful',
            'user' => $user,
        ],Response::HTTP_CREATED);
    }


        

    //criando a função de mostrar um usuario selecionado
    public function show($id){

        //Salvando na variavel user o usuario do banco que tenha o id tal
        $user = User::find($id);
        //se não existe o usuario me retorna um erro e resposta de usuario não encontrado
        if(!$user){
            return response()->json([
               'status' => 'error',
               'message' => 'user not found', 
                ],Response::HTTP_NOT_FOUND);
        }
        //se tudo ocorrer certo me retorna o usuario atual e a resposta ok
        return response()->json($user, Response::HTTP_OK);
    }

        
        //criando a função para atualizar o usuario atual
        public function update(Request $request , $id){
            //pegando meu usuario atual e salvando dentro da variavel user
            $user = User::find($id);
            //checando se o usuario não existe 
            if(!$user){
                //me retornando um json com erro 
                return response()->json(['status'=> 'error',
                'message' => 'user not found'], Response::HTTP_NOT_FOUND);
            }

            //caso o usuario exista faço a validação dos campos
            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $id,
                'password' => 'sometimes|required|string|min:3|max:8|confirmed'
            ]);

            //verificando se existe os campos preenchidos se sim modifique os valores
            if($request->has('name')){
                $user->name = $request->name;
            }
            if($request->has('email')){
                $user->email = $request->email;
            }
            if($request->has('password')){
                $user->password = Hash::make($request->password);
            }
            //Salvando a modificação
            $user->save();

            //me retornando o json com os dados do meu usuario e a mensagem de sucesso
            return response()->json([
                'status' => 'success',
                'message' => 'user update successful',
                'user' => $user,
            ],Response::HTTP_OK);
        }



        //criando a função para validar o token

        public function validarToken(Request $request){
            //salvando na variavel user o usuario que veio na requisição
            $user = $request->user();

            //se o usuario existe
            if($user){
                return response()->json(['
                status' => 'success',
                'message' => 'token is valid',
                'user' => $user],Response::HTTP_OK);
            }
            else{
                return response()->json(['status'=> 'error',
                 'message' => 'Invalid token'],Response::HTTP_UNAUTHORIZED);
            };
        }
}
