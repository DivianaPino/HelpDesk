<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\Respuesta;

class RespuestaNotiController extends Controller
{
    use Notifiable;

    public function marcar_como_leida($idNotificacion, $idTicket)
    {
        // Ticket
        $ticket=Ticket::find($idTicket);

         // Busca la notificación por su ID
        $notification = Auth::user()->notifications()->where('id', $idNotificacion)->firstOrFail();
        
        // Respuesta del ticket
        $respuestaTicket= Respuesta::find($notification->data['respuesta_id']);

         // Marca la notificación como leída
        $notification->markAsRead();
 
        return view('myViews.usuarioEst.respuesta')->with(['idTicket'=>$idTicket, 'ticket'=> $ticket,'respuesta' => $respuestaTicket]);   
    }
}
