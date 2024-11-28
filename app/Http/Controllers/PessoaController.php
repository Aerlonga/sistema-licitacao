<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use App\Models\Licitacao;

class PessoaController extends Controller
{
    // Exibir a lista de pessoas
    public function index()
    {
        $pessoas = Pessoa::all(); // Busca todas as pessoas
        return view('configuracoes', compact('pessoas')); // Passa os dados para a view
    }

    // Método para listar pessoas no formato JSON para o DataTables
    public function getPessoasData()
    {
        try {
            // Busca as pessoas (você pode personalizar a seleção de colunas)
            $pessoas = Pessoa::select(['id_pessoa', 'nome']);

            // Retorna os dados no formato JSON para o DataTables
            return DataTables::of($pessoas)->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar pessoas.'], 500);
        }
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
    // public function destroy($id)
    // {
    //     try {
    //         $pessoa = Pessoa::findOrFail($id);
    //         $pessoa->delete();

    //         // Verifica se a requisição espera JSON
    //         if (request()->expectsJson()) {
    //             return response()->json(['success' => 'Pessoa excluída com sucesso!'], 200);
    //         }

    //         return redirect()->route('configuracoes')->with('success', 'Pessoa excluída com sucesso!');
    //     } catch (\Exception $e) {
    //         Log::error('Erro ao excluir pessoa:', ['message' => $e->getMessage()]);

    //         if (request()->expectsJson()) {
    //             return response()->json(['error' => 'Erro ao excluir pessoa.'], 500);
    //         }

    //         return redirect()->route('configuracoes')->with('error', 'Erro ao excluir pessoa.');
    //     }
    // }

    public function destroy($id)
    {
        try {
            // Verificar se a pessoa existe
            $pessoa = Pessoa::findOrFail($id);

            // Verificar se há licitações associadas à pessoa e tratar a substituição
            $licitacoes = Licitacao::where('id_fiscal', $id)
                ->orWhere('id_gestor', $id)
                ->orWhere('id_integrante', $id)
                ->get();

            if ($licitacoes->isNotEmpty()) {
                // Vamos retornar um erro para o frontend, sugerindo a substituição
                return response()->json(['error' => 'A pessoa está sendo referenciada em licitações. Substitua os responsáveis primeiro.'], 400);
            }

            // Se não houver licitações associadas, podemos excluir a pessoa
            $pessoa->delete();

            // Retorna a resposta de sucesso
            return response()->json(['success' => 'Pessoa excluída com sucesso!'], 200);
        } catch (\Exception $e) {
            // Registra o erro e retorna a mensagem de erro
            Log::error('Erro ao excluir pessoa', [
                'message' => $e->getMessage(),
                'id' => $id
            ]);

            return response()->json(['error' => 'Erro ao excluir pessoa.'], 500);
        }
    }


    public function verificarAssociacao($id)
    {
        $licitacoes = Licitacao::where('id_fiscal', $id)
            ->orWhere('id_gestor', $id)
            ->orWhere('id_integrante', $id)
            ->count();

        if ($licitacoes > 0) {
            return response()->json(['error' => 'A pessoa está sendo referenciada em licitações. Substitua os responsáveis primeiro.'], 400);
        }

        return response()->json(['success' => 'A pessoa pode ser excluída.'], 200);
    }


    public function substituirResponsavel(Request $request, $id)
    {
        try {
            // Validação dos parâmetros
            $request->validate([
                'novo_fiscal' => 'required|exists:pessoas,id_pessoa',
                'novo_gestor' => 'required|exists:pessoas,id_pessoa',
                'novo_integrante' => 'required|exists:pessoas,id_pessoa',
            ]);

            // Atualiza as licitações que estão referenciando a pessoa excluída
            Licitacao::where('id_fiscal', $id)
                ->update(['id_fiscal' => $request->novo_fiscal]);

            Licitacao::where('id_gestor', $id)
                ->update(['id_gestor' => $request->novo_gestor]);

            Licitacao::where('id_integrante', $id)
                ->update(['id_integrante' => $request->novo_integrante]);

            // Agora podemos excluir a pessoa
            $pessoa = Pessoa::findOrFail($id);
            $pessoa->delete();

            return response()->json(['success' => 'Pessoa excluída com sucesso e responsáveis atualizados!'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao substituir responsável', [
                'message' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json(['error' => 'Erro ao substituir responsável.'], 500);
        }
    }
}
