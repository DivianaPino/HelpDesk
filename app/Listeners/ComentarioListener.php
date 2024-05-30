<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ComentarioNotification;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Ticket;

class ComentarioListener
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
   
        //* ASIGNAR LA NOTIFICACION DE COMENTARIO AL AGENTE TECNICO

        // Obtener el comentario desde el evento
        $comentario = $event->comentario;

        // Obtener el ticket asociado con el comentario
        $ticket = $comentario->ticket;
      
        //Acceder al nombre agente asignado del ticket
        $agenteAsignado = $ticket->asignado_a; 
      
        // Buscar el agente en el modelo User
        $usuario=User::where('name', $agenteAsignado)->first();

        // Asignar la notificacin al user (en este caso al agente tecnico)
        Notification::send($usuario, new ComentarioNotification($comentario, $usuario));
    }
}
 