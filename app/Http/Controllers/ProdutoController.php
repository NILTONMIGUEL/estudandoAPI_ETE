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

         $request->validate([
            'nome' => 'required|string|max:100',
            'descricao' => 'nullable'
         ]);
    }
    public function show($id){
        
       $produto = produtos::find($id);

       if(!$produto){
          return response()->json([
            'status'=> 'produto invalido']);

        return response()->json([
            "status" => "encontrado com sucesso",
            "dados" => $produto,
        ] , Response::HTTP_OK);
       }
      
    }

    //criando o método para apagar o produto
    public function destroy(){

    }

}
