<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasInfoNotiController extends Controller
{
    public function marcar_como_leida($idNotificacion, $idTicket)
    {
        // Busca la notificación por su ID
        $notification = Auth::user()->notifications()->where('id', $idNotificacion)->firstOrFail();

        // Marca la notificación como leída
        $notification->markAsRead();

        return redirect()->route('ver_mensaje', ['idticket' =>$idTicket, 'idmensaje' => $notification->data['masInfo_id'] ]);    
    }
}
