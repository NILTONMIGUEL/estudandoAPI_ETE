<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();

        return response()->json($clientes, Response::HTTP_OK);
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $Cliente = Cliente::where('email', $request->email)->first();

        if(!$Cliente || !Hash::check($request->password, $Cliente->password)){

            return response()->json([
                'status' => 'Cliente não encontrado',
                'message' => 'credenciais invalidas',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $Cliente->createToken($request->email)->plainTextToken;

        return response()->json([
            'status' => 'successfull',
            'message' => 'login com sucesso',
            'token' => $token,
        ], Response::HTTP_OK);
    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'successfull',
            'message' => 'logout sucesso'
        ], Response::HTTP_OK);
    }
    public function register(Request $request)
    {
       
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'cpf' => 'required|string|size:11',
            'password' => ['required', 'confirmed', Password::min(6)->letters()->numbers()->symbols()->uncompromised()->mixedCase()],
        ]);
        
        $cliente = Cliente::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'password' => Hash::make($request->password)
        ]);
        
        return response()->json(['status'=> 'successful','message' => 'criado com sucesso', 'cliente' => $cliente], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $cliente = Cliente::find($id);
        if(!$cliente){
            return response()->json([
                'status' => 'falha',
                'message' => 'usuario não encontrado',

            ], Response::HTTP_NOT_EXTENDED);
        }

        return response()->json(['
        status' => 'successfull',
        'message' => 'usuario encotrado com sucesso',
        'cliente' => $cliente], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //

        dd("cheguei aqui");
        $cliente = Cliente::find($id);

        if(!$cliente){
            return response()->json([
                'status' => 'error',
                'message'=> 'usuario não encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'nome'=> 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:cliente,email',$id,
            'cpf' => 'sometimes|required|size:11',
            'password' => ['required', 'confirmed', Password::min(6)->letters()->numbers()->symbols()->uncompromised()->mixedCase()],
        ]);

        if($request->has('name')){
            $cliente->name = $request->name;
        }
        if($request->has('email')){
            $cliente->email = $request->email;
        }
        if($request->has('cpf')){
            $cliente->cpf = $request->cpf;
        }
        
         if($request->has('password')){
            $cliente->password = Hash::make($request->password);
        }

        $cliente->save();

        return response()->json([
            'status' => 'successfull',
            'message' => 'usuario modificado com sucesso',
            'cliente' => $cliente,
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
