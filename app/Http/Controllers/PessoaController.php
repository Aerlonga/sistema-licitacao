<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Licitacao;

class PessoaController extends Controller
{

    public function index()
    {
        $pessoas = Pessoa::where('ativo', 1)->get(); // Busca apenas pessoas ativas
        return view('configuracoes', compact('pessoas'));
    }

    // Listar pessoas no formato JSON (API)
    // public function getAll()
    // {
    //     try {
    //         $pessoas = Pessoa::all(); // Busca todas as pessoas
    //         return response()->json($pessoas); // Retorna no formato JSON
    //     } catch (\Exception $e) {
    //         Log::error('Erro ao buscar pessoas:', ['message' => $e->getMessage()]);
    //         return response()->json(['error' => 'Erro ao buscar pessoas.'], 500);
    //     }
    // }

    public function getAll()
    {
        try {
            // Busca apenas pessoas ativas
            $pessoas = Pessoa::where('ativo', 1)->get();
            return response()->json($pessoas); // Retorna os dados como JSON
        } catch (\Exception $e) {
            Log::error('Erro ao buscar pessoas:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao buscar pessoas.'], 500);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255', // Validações
        ]);

        try {
            $pessoa = Pessoa::create(['nome' => $request->nome]); // Criação

            return response()->json([
                'success' => 'Pessoa adicionada com sucesso!',
                'data' => $pessoa,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar pessoa:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao adicionar pessoa.'], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        try {
            $pessoa = Pessoa::findOrFail($id);
            $pessoa->update(['nome' => $request->nome]); // Atualiza o registro

            return response()->json([
                'success' => 'Pessoa atualizada com sucesso!',
                'data' => $pessoa,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar pessoa:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao atualizar pessoa.'], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $pessoa = Pessoa::findOrFail($id);

            // Verificar se a pessoa está vinculada a uma licitação
            $temVinculos = Licitacao::where('id_gestor', $id)
                ->orWhere('id_integrante', $id)
                ->orWhere('id_fiscal', $id)
                ->exists();

            if ($temVinculos) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'error' => 'Essa pessoa está vinculada a uma ou mais licitações. Atualize as licitações antes de excluí-la.'
                    ], 400);
                }

                return redirect()->route('configuracoes')
                    ->with('error', 'Essa pessoa está vinculada a uma ou mais licitações. Atualize as licitações antes de excluí-la.');
            }

            // Marcar como inativa
            $pessoa->ativo = 0;
            $pessoa->save();

            if (request()->expectsJson()) {
                return response()->json(['success' => 'Pessoa excluída com sucesso!']);
            }

            return redirect()->route('configuracoes')
                ->with('success', 'Pessoa excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao inativar pessoa:', ['message' => $e->getMessage()]);

            if (request()->expectsJson()) {
                return response()->json(['error' => 'Erro ao inativar a pessoa.'], 500);
            }

            return redirect()->route('configuracoes')
                ->with('error', 'Erro ao inativar a pessoa.');
        }
    }
}
