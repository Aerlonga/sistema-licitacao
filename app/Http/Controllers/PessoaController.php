<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PessoaController extends Controller
{
    // Exibir a lista de pessoas
    public function index()
    {
        $pessoas = Pessoa::all(); // Busca todas as pessoas
        return view('configuracoes', compact('pessoas')); // Passa os dados para a view
    }

    // Listar pessoas no formato JSON (API)
    public function getAll()
    {
        try {
            $pessoas = Pessoa::all(); // Busca todas as pessoas
            return response()->json($pessoas); // Retorna no formato JSON
        } catch (\Exception $e) {
            Log::error('Erro ao buscar pessoas:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao buscar pessoas.'], 500);
        }
    }

    // Salvar uma nova pessoa
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255', // Validações
        ]);

        try {
            $pessoa = Pessoa::create(['nome' => $request->nome]); // Criação
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => 'Pessoa adicionada com sucesso!',
                    'data' => $pessoa,
                ], 201);
            }
            

            return redirect()->route('configuracoes')->with('success', 'Pessoa adicionada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao salvar pessoa:', ['message' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Erro ao adicionar pessoa.'], 500);
            }

            return redirect()->route('configuracoes')->with('error', 'Erro ao adicionar pessoa.');
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        try {
            $pessoa = Pessoa::findOrFail($id);
            $pessoa->update(['nome' => $request->nome]);

            if ($request->expectsJson()) {
                return response()->json(['success' => 'Pessoa atualizada com sucesso!', 'data' => $pessoa], 200);
            }

            return redirect()->route('configuracoes')->with('success', 'Pessoa atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar pessoa:', ['message' => $e->getMessage()]);
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Erro ao atualizar pessoa.'], 500);
            }
            return redirect()->route('configuracoes')->with('error', 'Erro ao atualizar pessoa.');
        }
    }


    // Excluir uma pessoa
    public function destroy($id)
    {
        try {
            $pessoa = Pessoa::findOrFail($id);
            $pessoa->delete();

            // Verifica se a requisição espera JSON
            if (request()->expectsJson()) {
                return response()->json(['success' => 'Pessoa excluída com sucesso!'], 200);
            }

            return redirect()->route('configuracoes')->with('success', 'Pessoa excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir pessoa:', ['message' => $e->getMessage()]);

            if (request()->expectsJson()) {
                return response()->json(['error' => 'Erro ao excluir pessoa.'], 500);
            }

            return redirect()->route('configuracoes')->with('error', 'Erro ao excluir pessoa.');
        }
    }
}
