<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MensajeClienteNotification;
use App\Models\User;


class MensajeClienteListener
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
        //* ASIGNAR LA NOTIFICACION DE respMasInfo AL AGENTE TECNICO QUE TIENE ASIGNADO  EL TICKET

        // Obtener la respuesta desde el evento
        
         $mensaje = $event->mensaje;

         // Obtener el ticket asociado con el comentario
         $ticket = $mensaje->ticket;

         //Acceder al nombre agente asignado del ticket
         $agenteAsignado = $ticket->asignado_a; 
       
         // Buscar el agente en el modelo User
         $usuario=User::where('name', $agenteAsignado)->first();
 
         // Asignar la notificacion al user (en este caso al agente tecnico)
         Notification::send($usuario, new MensajeClienteNotification($mensaje, $usuario));


    }
}
