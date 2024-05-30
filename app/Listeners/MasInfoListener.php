<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MasInfoNotification;
use App\Models\User;
use App\Models\Ticket;
use App\Models\MasInformacion;

class MasInfoListener
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
        //* ASIGNAR LA NOTIFICACION DE MAS_INFO DEL TICKET AL USUARIO (CLIENTE) CORRESPONDIENTE

        // Obtener el masInfo desde el evento
        
        $masInfo = $event->masInfo;

        $ticketId= $masInfo->ticket_id;

        $ticket= Ticket::find($ticketId);

        $usuario=User::find($ticket->user_id);

         // Asignar la notificacion al usuario a quien pertenece el ticket
        Notification::send($usuario, new MasInfoNotification($masInfo));
    }
}
