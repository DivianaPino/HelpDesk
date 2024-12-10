<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\User;


class MensajeClienteNotiController extends Controller
{
    public function marcar_como_leida($idNotificacion, $idTicket)
    {
        // Busca la notificación por su ID
        $notification = Auth::user()->notifications()->where('id', $idNotificacion)->firstOrFail();

        // Ticket
        $ticket=Ticket::find($idTicket);
        
        // Tecnico
        $tecnico=User::where('name', $ticket->asignado_a)->first();

        // Marca la notificación como leída
        $notification->markAsRead();

        return view('myViews.usuarioEst.ticketReportado')->with(['ticket'=> $ticket, 'idTicket' => $idTicket, 'tecnico'=> $tecnico]);

      
    }
}
