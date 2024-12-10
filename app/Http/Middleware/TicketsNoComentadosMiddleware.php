<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TicketHistorial;
use App\Models\Comentario;
use App\Models\Ticket;
use App\Models\Area;
use App\Models\Estado;
use Carbon\Carbon;

class TicketsNoComentadosMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $areas = Area::all();

        // foreach($areas as $area){
          
        //         // Obtener todos los tickets para esta área
        //     $estadoResuelto= Estado::where('nombre', 'Resuelto')->first();
        //     $ticketsResueltos = Ticket::where('area_id', $area->id)->where('estado_id', $estadoResuelto->id)->get();

        //     $estadoCerrado= Estado::where('nombre', 'Cerrado')->first();

        //     $fechaLimite = Carbon::now()->subDays(7);
          
        //     // Iterar sobre cada ticket resuelto
        //     foreach ($ticketsResueltos as $ticket) {
        //         // Verificar si la última actualización fue hace exactamente 7 días
        //         if ($ticket->updated_at < $fechaLimite) {
                  
        //             // Cambiar el estado a "cerrado"
        //             $ticket->estado_id = $estadoCerrado->id;
        //             $ticket->save();

        //             $historial= new TicketHistorial();
        //             $historial->ticket_id= $ticket->id;
        //             $historial->estado_id=$estadoCerrado->id;
        //             $historial->updated_at= Carbon::now();
        //             $historial->save();

        //             $comentario= new Comentario();
        //             $comentario->ticket_id=$ticket->id;
        //             $comentario->bool_reabrir=false;
        //             $comentario->save();
                   

        //         }
        //     }
            
        // }
        return $next($request);
    }
    
}
