<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacaoLicitacao extends Mailable
{
    use Queueable, SerializesModels;

    public $assunto;
    public $mensagem;

    /**
     * Cria uma nova instância de mensagem.
     *
     * @param string $assunto
     * @param string $mensagem
     */
    public function __construct($assunto, $mensagem)
    {
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
    }

    /**
     * Define o envelope da mensagem.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->assunto,
            from: new Address('aerlon.aga@gmail.com', 'Equipe de Licitações')
        );
    }

    /**
     * Define o conteúdo da mensagem.
     */
    public function content(): Content
    {
        return new Content(
            view: 'notificacao',
            with: [
                'mensagem' => $this->mensagem,
                'assunto' => $this->assunto,
            ]
        );
    }

    /**
     * Anexar arquivos ao email.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
