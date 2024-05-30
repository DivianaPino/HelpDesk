<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RespuestaNotification;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Clasificacion;
use App\Models\Area;

class RespuestaListener
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
        //* ASIGNAR LA NOTIFICACION DE RESPUESTA DEL TICKET AL USUARIO (CLIENTE) CORRESPONDIENTE

        // Obtener la respuesta desde el evento
        $respuesta = $event->respuesta;

        $ticketId= $respuesta->ticket_id;

        $ticket= Ticket::find($ticketId);

        $usuario=User::find($ticket->user_id);

         // Asignar la notificacion al usuario a quien pertenece el ticket
        Notification::send($usuario, new RespuestaNotification($respuesta));

    }
}
