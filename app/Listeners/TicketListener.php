<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketNotification;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Clasificacion;
use App\Models\Area;

class TicketListener
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
        //* ASIGNAR LA NOTIFICACION DE TICKET A LOS AGENTES TECNICOS DE AREA CORRESPONDIENTE

        // Obtener el ticket desde el evento
        $ticket = $event->ticket;

        //Clasificacion
        $clasificacion= Clasificacion::find($ticket->clasificacion_id);
  
        // Buscar el area en el modelo Area
        $area = Area::where('nombre', $clasificacion->nombre)->first();
 
        // Usuarios que pertecen al area area
        $usuariosEnArea = $area->users;
       
        // Asignar la notificación a cada usuario (agente tecnico) del área correspondiente
        foreach ($usuariosEnArea as $usuario) {
            Notification::send($usuario, new TicketNotification($ticket));
        }
    }
}
