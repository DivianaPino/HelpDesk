<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Notification;

class TicketNotiController extends Controller
{
    use Notifiable;

    public function marcar_como_leida($idNotificacion, $idTicket)
    {

        $usuario= Auth::user(); 

        $ticket=Ticket::find($idTicket);

        //* Busqueda de la notificación por su ID (del usuario que la leyo, es decir del autenticado)
        $notificationUser = Auth::user()->notifications()->where('id', $idNotificacion)->firstOrFail();

        //* Marcar la notificación como leída, (la notificacion que se leyo inicialmente)
        $notificationUser->markAsRead();

        //* Todas las notificaciones de tipo TicketNotification
        // $notificationsAll=Notification::where('type', 'App\Notifications\TicketNotification')->get();

        //* Marcar cada una como leida
        // foreach ($notificationsAll as $notification) {
        //     // Acceder a los datos de cada notificación individualmente
        //     if($notification->data['ticket_id'] == $idTicket){
        //         $notification->markAsRead();
        //     }
        // }
 
        return view('myViews.Admin.tickets.detalles', compact('ticket', 'usuario'));    
    }
}
