<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Respuesta;
use App\Models\TicketHistorial;
use App\Models\MasInformacion;
use App\Models\RespMasInfo;

class RespMasInfoNotiController extends Controller
{
    public function marcar_como_leida($idNotificacion, $idTicket)
    {
        // Busca la notificación por su ID
        $notification = Auth::user()->notifications()->where('id', $idNotificacion)->firstOrFail();

        // Marca la notificación como leída
        $notification->markAsRead();

        //Obtener el historial que esta en la posicion que viene en el parametro $idMensaje (ya que pueden haber registros eliminados)
        $hist_Posicion=TicketHistorial::where('ticket_id', $idTicket)->where('estado_id', 3)->whereNotNull('masinfo_id')
                                                                                             ->orderBy('id', 'desc')->first();
        $idMasInfo=$hist_Posicion->masinfo_id; 
      
         //ultimo Mensaje del Agente  
        $mensaje=MasInformacion::where('id', $idMasInfo)->latest('created_at')->first();

        // respuesta usuario
        $respuesta=RespMasInfo::where('masInfo_id', $mensaje->id)->first();

        if (Respuesta::where('ticket_id', $idTicket)->exists()) {

            $solucion=Respuesta::where('ticket_id', $idTicket)->first();

            return view('myViews.Admin.tickets.ticketRespondido', compact('idTicket', 'mensaje', 'idMasInfo', 'respuesta', 'solucion'));
             
        }else{
            return view('myViews.Admin.tickets.verRespCliente_masInfo', compact('idTicket', 'mensaje', 'idMasInfo', 'respuesta'));
        }
      
    }
}
