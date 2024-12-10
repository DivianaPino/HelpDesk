<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;


class msjTecnicoMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $mensaje;
    public $cliente;
    public $tecnico;
    public $idTicket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
   // Para el primer caso (msjTecnicoMail)

public function __construct($mensaje, $cliente, $tecnico, $idTicket)
{
    $this->mensaje = $mensaje;
    $this->cliente = $cliente;
    $this->tecnico = $tecnico;
    $this->idTicket = $idTicket;
}

/**
 * Build the message.
 *
 * @return $this
 */
public function build()
{
    return $this->markdown('emails.msjTecnicoMail')
        ->subject('El técnico ha enviado un mensaje - ' . Carbon::now()->format('d/M/Y H:i:s'));
}

}
