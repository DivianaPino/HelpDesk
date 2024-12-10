<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ticketResueltoMailable;

class TicketResueltoCorreoListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function handle($event)
    {
        $mensaje = $event->mensaje;
        $cliente = $event->cliente;
        $tecnico = $event->tecnico;
        $idTicket = $event->idTicket;
        $this->sendNotificationResolved($mensaje, $cliente, $tecnico, $idTicket);

    }


    private function sendNotificationResolved($mensaje, $cliente,$tecnico,  $idTicket)
    {
        
        $email = $cliente->email;
      
        try {
            Mail::to($email)->send(new ticketResueltoMailable($mensaje, $cliente, $tecnico, $idTicket));
            return "Correo enviado a {$email}";
        } catch (\Exception $e) {
             \Log::error("Error al enviar correo al cliente: ", ['exception' => $e]);
            return "Error al enviar correo a {$email}: " . $e->getMessage();
        }
        
    }
}
