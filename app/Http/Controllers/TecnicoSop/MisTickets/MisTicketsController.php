<?php

namespace App\Http\Controllers\TecnicoSop\MisTickets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Estado;

class MisTicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function misTickets_agenteTecnico()
    {
        $usuario= Auth::user();
        // Tickets asignados al técnico autenticado
        $tickets = Ticket::where('asignado_a', $usuario->name )->get();

       
        // Cantidad de tickets por estados
        $cant_tkt_abiertos=$tickets->where('estado_id', 2 )->count();
        $cant_tkt_enEspera=$tickets->where('estado_id', 3 )->count();
        $cant_tkt_resueltos=$tickets->where('estado_id', 4)->count();
        $cant_tkt_reAbiertos=$tickets->where('estado_id', 5)->count();
        $cant_tkt_cerrados=$tickets->where('estado_id', 6)->count();

   
         // Cantidad de tickets vencidos que estan asignados al técnico autenticado
         $estados = Estado::whereIn('nombre', ['Nuevo', 'Abierto', 'Reabierto'])->pluck('id');
         $fecha_actual=Carbon::now();
         $cant_tkt_vencidos = $tickets->whereIn('estado_id', $estados)->where('fecha_caducidad', '<', $fecha_actual)->count();
      
        return view('myViews.tecnicoSop.misTickets.index',compact('tickets',  'cant_tkt_abiertos',
                                                                              'cant_tkt_enEspera',
                                                                              'cant_tkt_resueltos',
                                                                              'cant_tkt_reAbiertos',
                                                                              'cant_tkt_cerrados',
                                                                              'cant_tkt_vencidos'
                                                                                                
                                                          ));
       
    }

    //*------------------------TICKETS POR ESTADOS QUE ESTAN ASIGNADOS AL AGENTE TECNICO AUTENTICADO:----------------------------

    public function tickets_abiertos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
 
        $estadoAbierto = Estado::where('nombre', 'abierto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsAbiertos = Ticket::where('asignado_a', $usuario->name )->whereIn('area_id', $areasUsuario)->where('estado_id', $estadoAbierto->id)->get();


        return view('myViews.tecnicoSop.misTickets.abiertos')->with('tickets', $ticketsAbiertos) ;
    }

    public function tickets_enEspera()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_enEspera = Estado::where('nombre', 'En espera')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_enEspera = Ticket::where('asignado_a', $usuario->name )->whereIn('area_id', $areasUsuario)->where('estado_id', $estado_enEspera->id)->get();

        return view('myViews.tecnicoSop.misTickets.enEspera')->with('tickets', $tickets_enEspera) ;
             
    }

    public function tickets_enRevision()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_enRevision = Estado::where('nombre', 'En revisión')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_enRevision = Ticket::where('asignado_a', $usuario->name )->whereIn('area_id', $areasUsuario)->where('estado_id', $estado_enRevision->id)->get();

        return view('myViews.tecnicoSop.misTickets.enRevision')->with('tickets', $tickets_enRevision) ;
             
    }


    public function tickets_vencidos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');

        $estados = Estado::whereIn('nombre', ['nuevo', 'abierto'])->pluck('id');
        
        $fecha_actual=Carbon::now();

        $ticketsVencidos = Ticket::where('asignado_a', $usuario->name )->whereIn('area_id', $areasUsuario)->whereIn('estado_id', $estados)->where('fecha_caducidad', '<', $fecha_actual)->get();

        return view('myViews.tecnicoSop.misTickets.vencidos')->with('tickets', $ticketsVencidos) ;
    }
    
    
    public function tickets_resueltos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estadoResuelto = Estado::where('nombre', 'Resuelto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsResueltos = Ticket::where('asignado_a', $usuario->name )->whereIn('area_id', $areasUsuario)->where('estado_id', $estadoResuelto->id)->get();

        return view('myViews.tecnicoSop.misTickets.resueltos')->with('tickets', $ticketsResueltos) ;
    }

    public function tickets_cerrados()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estadoCerrado = Estado::where('nombre', 'Cerrado')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsCerrado =Ticket::where('asignado_a', $usuario->name )->whereIn('area_id', $areasUsuario)->where('estado_id', $estadoCerrado->id)->get();

        return view('myViews.tecnicoSop.misTickets.cerrados')->with('tickets', $ticketsCerrado) ;
    }
    
    public function tickets_reAbiertos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_reAbierto = Estado::where('nombre', 'Reabierto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_reAbierto = Ticket::where('asignado_a', $usuario->name )->whereIn('area_id', $areasUsuario)->where('estado_id', $estado_reAbierto->id)->get();

        return view('myViews.tecnicoSop.misTickets.reAbiertos')->with('tickets', $tickets_reAbierto) ;
    }

 
}
