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

    public function getAll()
    {
        try {
            // Atualize para buscar 'id_pessoa' em vez de 'id'
            $pessoas = Pessoa::where('ativo', 1)->get(['id_pessoa', 'nome', 'sobrenome']);
            return response()->json($pessoas);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar pessoas:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao buscar pessoas.'], 500);
        }
    }



    public function store(Request $request)
    {
        $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ\s]+$/', // Permite apenas letras (incluindo acentos) e espaços
                'not_regex:/^\d+$/' // Bloqueia entradas que contenham apenas números
            ],
            'sobrenome' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ\s]+$/',
                'not_regex:/^\d+$/'
            ],
        ]);

        try {
            // Capitaliza a primeira letra do nome e sobrenome
            $nome = ucfirst(strtolower($request->nome));
            $sobrenome = ucfirst(strtolower($request->sobrenome));

            // Verifica se já existe uma pessoa com o mesmo nome e sobrenome
            $existe = Pessoa::where('nome', $nome)
                ->where('sobrenome', $sobrenome)
                ->exists();

            if ($existe) {
                return response()->json(['error' => 'Essa pessoa já está cadastrada.'], 400);
            }

            // Cria a nova pessoa
            $pessoa = Pessoa::create([
                'nome' => $nome,
                'sobrenome' => $sobrenome,
            ]);

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
            'nome' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ\s]+$/',
                'not_regex:/^\d+$/'
            ],
            'sobrenome' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-zÀ-ÿ\s]+$/',
                'not_regex:/^\d+$/'
            ],
        ]);

        try {
            // Capitaliza a primeira letra do nome e sobrenome
            $nome = ucfirst(strtolower($request->nome));
            $sobrenome = ucfirst(strtolower($request->sobrenome));

            // Verifica se já existe uma pessoa com o mesmo nome e sobrenome (excluindo o ID atual)
            $existe = Pessoa::where('nome', $nome)
                ->where('sobrenome', $sobrenome)
                ->where('id_pessoa', '!=', $id)
                ->exists();

            if ($existe) {
                return response()->json(['error' => 'Essa pessoa já está cadastrada.'], 400);
            }

            // Atualiza o registro da pessoa
            $pessoa = Pessoa::findOrFail($id);
            $pessoa->update([
                'nome' => $nome,
                'sobrenome' => $sobrenome,
            ]);

            return response()->json([
                'success' => 'Pessoa atualizada com sucesso!',
                'data' => $pessoa,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar pessoa:', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao atualizar pessoa.'], 500);
        }
    }


    // public function destroy($id)
    // {
    //     try {
    //         $pessoa = Pessoa::findOrFail($id);

    //         // Verificar se a pessoa está vinculada a uma licitação
    //         $temVinculos = Licitacao::where('id_gestor', $id)
    //             ->orWhere('id_integrante', $id)
    //             ->orWhere('id_fiscal', $id)
    //             ->exists();

    //         if ($temVinculos) {
    //             if (request()->expectsJson()) {
    //                 return response()->json([
    //                     'error' => 'Essa pessoa está vinculada a uma ou mais contratações. Atualize antes de excluí-la.'
    //                 ], 400);
    //             }

    //             return redirect()->route('configuracoes')
    //                 ->with('error', 'Essa pessoa está vinculada a uma ou mais contratações. Atualize antes de excluí-la.');
    //         }

    //         // Marcar como inativa
    //         $pessoa->ativo = 0;
    //         $pessoa->save();

    //         if (request()->expectsJson()) {
    //             return response()->json(['success' => 'Pessoa excluída com sucesso!']);
    //         }

    //         return redirect()->route('configuracoes')
    //             ->with('success', 'Pessoa excluída com sucesso!');
    //     } catch (\Exception $e) {
    //         Log::error('Erro ao inativar pessoa:', ['message' => $e->getMessage()]);

    //         if (request()->expectsJson()) {
    //             return response()->json(['error' => 'Erro ao inativar a pessoa.'], 500);
    //         }

    //         return redirect()->route('configuracoes')
    //             ->with('error', 'Erro ao inativar a pessoa.');
    //     }
    // }

    public function destroy($id)
    {
        try {
            // Encontra a pessoa pelo ID
            $pessoa = Pessoa::findOrFail($id);

            // Verificar se a pessoa está vinculada a uma licitação com status ativo
            $temVinculos = Licitacao::where('status', 1) // Substituindo 'ativo' por 'status'
                ->where(function ($query) use ($id) {
                    $query->where('id_gestor', $id)
                        ->orWhere('id_integrante', $id)
                        ->orWhere('id_fiscal', $id);
                })
                ->exists();

            if ($temVinculos) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'error' => 'Essa pessoa está vinculada a uma ou mais contratações ativas. Atualize antes de excluí-la.'
                    ], 400);
                }

                return redirect()->route('configuracoes')
                    ->with('error', 'Essa pessoa está vinculada a uma ou mais contratações ativas. Atualize antes de excluí-la.');
            }

            // Marcar a pessoa como inativa
            $pessoa->ativo = 0;
            $pessoa->save();

            if (request()->expectsJson()) {
                return response()->json(['success' => 'Pessoa excluída com sucesso!']);
            }

            return redirect()->route('configuracoes')
                ->with('success', 'Pessoa excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao inativar pessoa com ID: $id", ['message' => $e->getMessage()]);

            if (request()->expectsJson()) {
                return response()->json(['error' => 'Erro ao inativar a pessoa.'], 500);
            }

            return redirect()->route('configuracoes')
                ->with('error', 'Erro ao inativar a pessoa.');
        }
    }
}
