<?php

namespace App\Http\Controllers\Admin\Analisis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\Estado;

class AnalisisController extends Controller
{

    public function __construct(){

        $this->middleware('can:indexAnalisis');
    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    }

 

}
