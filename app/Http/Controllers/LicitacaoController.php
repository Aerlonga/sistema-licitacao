<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Licitacao;
use App\Http\Requests\CadastroLicitacaoRequest;
use App\Service\LicitacaoService;
use App\Mail\NotificacaoLicitacao;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;

class LicitacaoController extends Controller
{
    private $licitacaoservice;

    public function __construct(LicitacaoService $licitacaoservice)
    {
        $this->licitacaoservice = $licitacaoservice;
    }

    public function inicio()
    {
        return view('inicio');
    }

    public function visualizarLicitacoes()
    {
        return view('licitacoes');
    }

    public function listarLicitacoes()
    {
        $licitacoes = Licitacao::where('status', 1)->get(); // Apenas registros ativos
        return view('licitacoeslista', compact('licitacoes'));
    }

    public function listarDatatable()
    {
        $licitacoes = Licitacao::where('status', 1)->with(['gestor', 'integrante', 'fiscal'])->get();
        return response()->json($licitacoes);
    }

    public function checklist()
    {
        return view('checklist');
    }

    public function gerarEquipe(CadastroLicitacaoRequest $request)
    {
        $request->validate([
            'objeto_contratacao' => 'required|string',
            'sei' => 'nullable|string',
            'sislog' => 'nullable|string',
            'modalidade' => 'nullable|string',
            'situacao' => 'required|string|in:Em andamento,Em outro setor,Finalizado',
            'local' => 'required|string|in:Licitação,Orçamentária,Jurídico,Ordenador de Despesas',
        ]);

        $mensagemsalva = $this->licitacaoservice->salvarlicitacao([
            'objeto_contratacao' => $request->input('objeto_contratacao'),
            'sei' => $request->input('sei'),
            'sislog' => $request->input('sislog'),
            'modalidade' => $request->input('modalidade'),
            'situacao' => $request->input('situacao'),
            'local' => $request->input('local'),
        ]);

        return redirect()->route('EquipeSalva')->with('success', $mensagemsalva);
    }

    public function show($id)
    {
        try {
            $licitacao = Licitacao::with(['gestor', 'integrante', 'fiscal'])->findOrFail($id);
            $pessoas = \App\Models\Pessoa::all(); // Carrega todas as pessoas

            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();

            $response = [
                'modalidade' => $licitacao->modalidade ?? '',
                'objeto_contratacao' => $licitacao->objeto_contratacao ?? '',
                'gestor' => $licitacao->gestor ? $licitacao->gestor->nome : '',
                'integrante' => $licitacao->integrante ? $licitacao->integrante->nome : '',
                'fiscal' => $licitacao->fiscal ? $licitacao->fiscal->nome : '',
                'data_inclusao' => $licitacao->created_at ? $licitacao->created_at->format('d/m/Y H:i:s') : '',
                'sei' => $licitacao->sei ?? '',
                'sislog' => $licitacao->sislog ?? '',
                'situacao' => $licitacao->situacao ?? '',
                'local' => $licitacao->local ?? '',
                'observacao' => $licitacao->observacao ?? '',
                'gestor_id' => $licitacao->id_gestor,
                'integrante_id' => $licitacao->id_integrante,
                'fiscal_id' => $licitacao->id_fiscal,
                'pessoas' => $pessoas,
            ];

            return response()->json([
                'data' => $response,
                'isAdmin' => $isAdmin
            ]);

            // return response()->json(['data' => $response]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Licitação não encontrada.'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar detalhes da licitação.',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $licitacao = Licitacao::findOrFail($id);
            $licitacao->status = 0; // Define como inativo
            $licitacao->save();

            return response()->json(['success' => 'Licitação marcada como inativa com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao inativar a licitação.'], 500);
        }
    }

    public function enviarEmailNotificacao($email, $assunto, $mensagem)
    {
        try {
            Mail::to($email)->send(new NotificacaoLicitacao($assunto, $mensagem));
            return response()->json(['success' => 'Notificação enviada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao enviar notificação: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Dados recebidos no update:', $request->all());
        try {
            $licitacao = Licitacao::findOrFail($id);

            if ($request->has('status') && $request->input('status') == 0) {
                /** @var \App\Models\User|null $user */
                $user = Auth::user(); // Adicionando a anotação de tipo aqui
                if ($user && $user->isAdmin()) {
                    $licitacao->status = 0;
                    $licitacao->save();
                    return response()->json(['success' => 'Licitação marcada como inativa com sucesso!']);
                } else {
                    return response()->json(['error' => 'Você não tem permissão para marcar esta licitação como inativa.'], 403);
                }
            }

            $validatedData = $request->validate([
                'id_gestor' => 'nullable|exists:pessoas,id_pessoa',
                'id_integrante' => 'nullable|exists:pessoas,id_pessoa',
                'id_fiscal' => 'nullable|exists:pessoas,id_pessoa',
                'objeto_contratacao' => 'required|string',
                'situacao' => 'required|string|in:Em andamento,Em outro setor,Finalizado',
                'local' => 'required|string|in:Licitação,Orçamentária,Jurídico,Ordenador de Despesas',
                'observacao' => 'nullable|string',
                'modalidade' => 'nullable|string',
                'sei' => 'nullable|string',
                'sislog' => 'nullable|string',
            ]);

            $licitacao->save();


            /** @var \App\Models\User|null $user */
            $user = Auth::user(); // Adicionando a anotação de tipo

            if (Auth::check() && $user && $user->isAdmin()) {
                $licitacao->id_gestor = $validatedData['id_gestor'] ?? $licitacao->id_gestor;
                $licitacao->id_integrante = $validatedData['id_integrante'] ?? $licitacao->id_integrante;
                $licitacao->id_fiscal = $validatedData['id_fiscal'] ?? $licitacao->id_fiscal;
                $licitacao->modalidade = $validatedData['modalidade'] ?? $licitacao->modalidade;
                $licitacao->objeto_contratacao = $validatedData['objeto_contratacao'] ?? $licitacao->objeto_contratacao;
                $licitacao->sei = $validatedData['sei'] ?? $licitacao->sei;
                $licitacao->sislog = $validatedData['sislog'] ?? $licitacao->sislog;
                $licitacao->situacao = $validatedData['situacao'];
                $licitacao->local = $validatedData['local'];
                $licitacao->observacao = $validatedData['observacao'];
            } else {
                $licitacao->situacao = $validatedData['situacao'];
                $licitacao->local = $validatedData['local'];
                $licitacao->observacao = $validatedData['observacao'];
            }

            $originalSituacao = $licitacao->situacao;
            $originalLocal = $licitacao->local;
            $originalObservacao = $licitacao->observacao;

            $licitacao->save();

            $alteracoes = [];
            if ($originalSituacao !== $licitacao->situacao) {
                $alteracoes[] = "Situação";
            }
            if ($originalLocal !== $licitacao->local) {
                $alteracoes[] = "Local";
            }
            if ($originalObservacao !== $licitacao->observacao) {
                $alteracoes[] = "Observação";
            }

            $email = 'aerlon.aga@gmail.com';
            $assunto = 'Atualização na Licitação';
            $nome = 'Destinatário';

            $mensagem = "<h3>Olá!</h3>";
            if (!empty($alteracoes)) {
                $camposAlterados = implode(', ', $alteracoes);
                $mensagem .= "<p>Os seguintes campos da licitação <strong>\"{$licitacao->objeto_contratacao}\"</strong> foram atualizados: <strong>{$camposAlterados}</strong>.</p>";
            } else {
                $mensagem .= "<p>A licitação com o objeto <strong>\"{$licitacao->objeto_contratacao}\"</strong> foi atualizada.</p>";
            }

            $mensagem .= "<p>Estamos notificando sobre uma atualização em sua licitação. Para mais detalhes, acesse o sistema.</p>";

            $this->enviarEmailNotificacao($email, $assunto, $mensagem);

            return response()->json(['success' => 'Situação, Local e Observação atualizados com sucesso!']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Licitação não encontrada.'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Dados inválidos.', 'message' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            // Adicione mais informações sobre o erro para ajudar na depuração
            return response()->json([
                'error' => 'Erro ao atualizar situação e local.',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }
}
