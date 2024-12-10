<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\Mensaje;
use App\Models\User;

class MensajeTecnicoNotiController extends Controller
{
    use Notifiable;

    public function marcar_como_leida($idNotificacion, $idTicket)
    {
        // Ticket
        $ticket=Ticket::find($idTicket);

         // Busca la notificación por su ID
        $notification = Auth::user()->notifications()->where('id', $idNotificacion)->firstOrFail();

         // Marca la notificación como leída
        $notification->markAsRead();

        $cliente=User::find($ticket->user_id);
 
        return view('myViews.Admin.tickets.form_msjTecnico')->with(['ticket'=> $ticket, 'idTicket' => $idTicket, 'cliente' => $cliente]);

    }
}
