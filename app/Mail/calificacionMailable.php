<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class calificacionMailable extends Mailable
{
    use Queueable, SerializesModels;


    public $idTicket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($idTicket)
    {
        $this->idTicket = $idTicket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.calificacionMail')
            ->subject('La asistencia del ticket ha sido calificada - ' . Carbon::now()->format('d/M/Y H:i:s'));
    }
}
