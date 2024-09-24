<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CalificacionNotification;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Ticket;

class CalificacionListener
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
   
        //* ASIGNAR LA NOTIFICACION DE CALIFICACION AL AGENTE TECNICO

        // Obtener la calificacion desde el evento
        $calificacion = $event->calificacion;

        // Obtener el ticket asociado con el comentario
        $ticket = $calificacion->ticket;
      
        //Acceder al nombre agente asignado del ticket
        $agenteAsignado = $ticket->asignado_a; 
      
        // Buscar el agente en el modelo User
        $usuario=User::where('name', $agenteAsignado)->first();

        // Asignar la notificacin al user (en este caso al agente tecnico)
        Notification::send($usuario, new CalificacionNotification($calificacion, $usuario));
    }
}
 