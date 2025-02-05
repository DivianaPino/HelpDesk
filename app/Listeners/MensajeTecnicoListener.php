<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MensajeTecnicoNotification;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Area;

class MensajeTecnicoListener
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
        //* ASIGNAR LA NOTIFICACION DE MENSAJE DEL TECNICO AL USUARIO (CLIENTE) CORRESPONDIENTE

        // Obtener la respuesta desde el evento
        $mensaje = $event->mensaje;

        $ticketId= $mensaje->ticket_id;

        $ticket= Ticket::find($ticketId);

        $usuario=User::find($ticket->user_id);

         // Asignar la notificacion al usuario a quien pertenece el ticket
        Notification::send($usuario, new MensajeTecnicoNotification($mensaje));

    }
}
