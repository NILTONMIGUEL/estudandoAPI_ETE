<?php

namespace App\Http\Controllers;

use App\Models\produtos;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProdutoController extends Controller
{
 
    public function index(){
       //retornando todos os meus produtos
        return produtos::all();
    }

    public function store(Request $request){
          $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric',
            'quantidade' => 'required|numeric'
          ]);

          
          $produto = produtos::create($request->all());
          return response()->json(['message' => 'Cadastrado com sucesso',
          'dados'=> $produto], Response::HTTP_OK);

          
    }
    //criando o método para criar os produtos

    //criando o método para atualizar o produto
    public function update(Request $request , $id){
          
         $produto = produtos::find($id);
        
         if(!$produto){
            return response()->json([
                'message' => 'error' 
            ], Response::HTTP_NOT_FOUND);
         }
         $request->validate([
            'nome' => 'sometimes|required|string|max:100',
            'descricao' => 'sometimes|nullable|string',
            'preco' => 'sometimes|required|numeric',
            'quantidade' => 'sometimes|required|numeric'
         ]);

         $produto::update($request->all());
         return response()->json(['message' => 'produto modificado com sucesso',
         'produto' => $produto], Response::HTTP_OK);
    }
    public function show($id){
        
       $produto = produtos::find($id);

       if(!$produto){
          return response()->json([
            'message'=> 'produto invalido']);

        return response()->json([
            "message" => "encontrado com sucesso",
            "dados" => $produto,
        ] , Response::HTTP_OK);
       }
      
    }

    //criando o método para apagar o produto
    public function destroy($id){

        $produto = produtos::find($id);
        if(!$produto){
            return response()->json(['message' => 'produto não encontrado'], Response::HTTP_NOT_FOUND);
        }

        $produto->delete();
        return response()->json(['message' => 'produto excluido com sucesso'], Response::HTTP_OK);

    }

}
