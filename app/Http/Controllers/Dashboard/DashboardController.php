<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Estado;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(){

        if(Auth::check()){
            if(Auth::user()->hasRole('Administrador')){
                $cant_tkt_nuevos=Ticket::where('estado_id', 1)->count();
                $cant_tkt_abiertos=Ticket::where('estado_id', 2)->count();
                $cant_tkt_enEspera=Ticket::where('estado_id', 3)->count();
                $cant_tkt_enRevision=Ticket::where('estado_id', 4)->count();
                $cant_tkt_resueltos=Ticket::where('estado_id', 5)->count();
                $cant_tkt_reAbiertos=Ticket::where('estado_id', 6)->count();
                $cant_tkt_cerrados=Ticket::where('estado_id', 7)->count();
        
                // Cantidad de tickets vencidos 
                $estados = Estado::whereIn('nombre', ['Nuevo', 'Abierto', 'Reabierto'])->pluck('id');
                $fecha_actual=Carbon::now();
                $cant_tkt_vencidos = Ticket::whereIn('estado_id', $estados)->where('fecha_caducidad', '<', $fecha_actual)->count();
                return view('myViews.Admin.analisis.index', compact('cant_tkt_nuevos',
                                                                    'cant_tkt_abiertos',
                                                                    'cant_tkt_enEspera',
                                                                    'cant_tkt_enRevision', 
                                                                    'cant_tkt_resueltos',
                                                                    'cant_tkt_reAbiertos',
                                                                    'cant_tkt_cerrados',
                                                                    'cant_tkt_vencidos' 
                                                                ));
                // return view('myViews.Admin.tickets.index')->with('tickets', $tickets);
                // dd('administrador');
            }else if(Auth::user()->hasRole(['Jefe de área','Técnico de soporte'])){
                    
                return redirect()->route('areaUsuario_tickets');
            
            }else{
                 $tickets=Ticket::where('user_id', auth()->user()->id)->get();
                 return view('myViews.usuarioEst.index')->with('tickets', $tickets);
                // dd('otro user');
    
            }
        }
    } 


}


