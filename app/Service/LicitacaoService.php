<?php

namespace App\Service;

use App\Models\Licitacao;
use App\Models\Pessoa;
use App\Models\SequenciaLicitacao;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\Service\NotificacaoService;

DB::statement("ALTER TABLE licitacoes AUTO_INCREMENT = 1;");
DB::statement("ALTER TABLE sequencia_licitacao AUTO_INCREMENT = 1;");

class LicitacaoService
{
    private $notificacaoService;

    public function __construct(NotificacaoService $notificacaoService)
    {
        $this->notificacaoService = $notificacaoService;
    }

    public function salvarlicitacao($dados)
    {
        $pessoas = Pessoa::all();
        $pessoasIds = $pessoas->pluck('id_pessoa')->toArray();
        shuffle($pessoasIds);

        $idGestor = $pessoasIds[0];
        $idIntegrante = $pessoasIds[1];
        $idFiscal = $pessoasIds[2];

        $licitacao = [
            'id_gestor' => $idGestor,
            'id_integrante' => $idIntegrante,
            'id_fiscal' => $idFiscal,
            'objeto_contratacao' => $dados['objeto_contratacao'],
            'sei' => $dados['sei'],
            'sislog' => $dados['sislog'],
            'modalidade' => $dados['modalidade'],
            'situacao' => $dados['situacao'],
            'local' => $dados['local'],
        ];

        $licitacaosalva = Licitacao::create($licitacao);

        // Chamada para o serviço de notificação
        $email = 'aerlon.aga@gmail.com';
        $assunto = 'Nova Licitação Criada';
        $mensagem = "Uma nova licitação foi criada com o objeto: {$dados['objeto_contratacao']}.";
        $nome = 'Gestor';

        $this->notificacaoService->enviarEmailNotificacao($email, $assunto, $mensagem, $nome);

        $existesequencia = $this->sequenciaLicitacaoExistente($idGestor, $idIntegrante, $idFiscal);

        if ($existesequencia) {
            return 'Existe essa sequência! Salvo com sucesso!';
        } else {
            $sequenciaLicitacao = [
                'id_gestor' => $idGestor,
                'id_integrante' => $idIntegrante,
                'id_fiscal' => $idFiscal,
                'updated_at' => new DateTime(),
                'created_at' => new DateTime(),
            ];
            SequenciaLicitacao::create($sequenciaLicitacao);
            return 'Sequência não existe! Salvo com sucesso!';
        }
    }

    private function sequenciaLicitacaoExistente($idGestor, $idIntegrante, $idFiscal)
    {
        $sequencia = SequenciaLicitacao::where('id_gestor', $idGestor)
            ->where('id_integrante', $idIntegrante)
            ->where('id_fiscal', $idFiscal)
            ->first();

        return !is_null($sequencia);
    }
}
