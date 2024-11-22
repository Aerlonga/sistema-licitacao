<?php

namespace App\Service;

use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacaoLicitacao;
use Illuminate\Support\Facades\Log;

class NotificacaoService
{
    public function enviarEmailNotificacao($email, $assunto, $mensagem)
    {
        try {
            Mail::to($email)->send(new NotificacaoLicitacao($assunto, $mensagem));
            Log::info("Notificação enviada com sucesso para {$email}");
        } catch (\Exception $e) {
            Log::error('Erro ao enviar notificação: ' . $e->getMessage());
        }
    }
}