<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Mensaje;
use App\Models\User;


class CalificacionNotiController extends Controller
{
    use Notifiable;

    public function marcar_como_leida($idNotificacion, $idTicket)
    {
        // Busca la notificación por su ID
        $notification = Auth::user()->notifications()->where('id', $idNotificacion)->firstOrFail();

        // Marca la notificación como leída
        $notification->markAsRead();

        // Ticket
        $ticket=Ticket::find($idTicket);

        $cliente=User::find($ticket->user_id);

        return view('myViews.Admin.tickets.form_msjTecnico')->with(['ticket'=> $ticket, 'idTicket' => $idTicket, 'cliente' => $cliente]);    
    }
}
