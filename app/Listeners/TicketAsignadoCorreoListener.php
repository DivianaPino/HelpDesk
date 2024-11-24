<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ticketAsignadoMailable;

class TicketAsignadoCorreoListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
       
        $ticket = $event->ticket;
        $tecnico = $event->tecnico;
        $this->sendNotificationToSupportTechnician($ticket, $tecnico);
        
    }

    private function sendNotificationToSupportTechnician($ticket, $tecnico)
    {
        
        $email = $tecnico->email;

        try {
            Mail::to($email)->send(new ticketAsignadoMailable($ticket));
            return "Correo enviado a {$email}";
        } catch (\Exception $e) {
            return "Error al enviar correo a {$email}: " . $e->getMessage();
        }
        
    }
    
}
