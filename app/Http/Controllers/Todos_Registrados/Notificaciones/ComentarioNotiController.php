<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Respuesta;
use App\Models\Comentario;

class ComentarioNotiController extends Controller
{
    use Notifiable;

    public function marcar_como_leida($idNotificacion, $idTicket, $idRespuesta)
    {
        // Busca la notificación por su ID
        $notification = Auth::user()->notifications()->where('id', $idNotificacion)->firstOrFail();

        // Marca la notificación como leída
        $notification->markAsRead();

        // Ticket
        $ticket=Ticket::find($idTicket);

        // Respuesta
        $respuesta=Respuesta::find($idRespuesta);

        // Comentario
        $idComentario=$notification->data['comentario_id'];
        $comentario=Comentario::find($idComentario);
 
        return view('myViews.tecnicoSop.comentarios', compact('idTicket', 'ticket', 'respuesta', 'comentario'));    
    }
}
