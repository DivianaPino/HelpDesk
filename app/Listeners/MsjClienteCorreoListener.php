<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\msjClienteMailable;

class MsjClienteCorreoListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $mensaje = $event->mensaje;
        $cliente = $event->cliente;
        $tecnico = $event->tecnico;
        $idTicket = $event->idTicket;
        $this->sendNotificationToSupportAgent($mensaje, $cliente, $tecnico, $idTicket);

    }

    private function sendNotificationToSupportAgent($mensaje, $cliente,$tecnico,  $idTicket)
    {
        
        $email = $tecnico->email;
    
        try {
            Mail::to($email)->send(new msjClienteMailable($mensaje, $cliente, $tecnico, $idTicket));
            return "Correo enviado a {$email}";
        } catch (\Exception $e) {
             \Log::error("Error al enviar correo técnico: ", ['exception' => $e]);
            return "Error al enviar correo a {$email}: " . $e->getMessage();
        }
        
    }
}
