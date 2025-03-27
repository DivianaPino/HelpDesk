<?php

namespace App\Http\Controllers\Admin\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Reporte;
use App\Models\Area;
use App\Models\Estado;
use Carbon\Carbon;
use PDF;

class ReporteController extends Controller
{
    public function reporte(){
        $tickets=Ticket::with(['user', 'area', 'prioridad', 'estado'])->get();
        $areas= Area::all();
        $estados= Estado::all();
        return view('myViews.Admin.reporte.reporte', compact('tickets', 'areas', 'estados'));
    }

    public function reporteFiltrados(Request $request)
    {
        $areas = Area::all();
        $estados = Estado::all();
        $primerTicket = Ticket::orderBy('created_at', 'asc')->first();
    
        $fecha_inicial = $request->input('fecha_inicial');
        $fecha_actual = Carbon::now();

        $fecha_fin = $request->input('fecha_fin');
        $area_id = $request->input('area'); 
        $estado_id = $request->input('estado'); 
    
        // Guardar datos en sesión
        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_fin' => $fecha_fin]);
    
        // Guardar área y estado en sesión
        session(['area_id' => $area_id === 'all' ? 'all' : $area_id]);
        session(['estado_id' => $estado_id === 'all' ? 'all' : $estado_id]);
    
        $query = Ticket::with(['user', 'area', 'prioridad', 'estado']);
    
        // Filtrado por fechas
        if (!empty($fecha_inicial) && !empty($fecha_fin)) {
            if ($fecha_inicial == $fecha_fin) {
                $query->whereDate('created_at', $fecha_inicial);
            } else {
                $fecha_inicial = Carbon::parse($fecha_inicial)->startOfDay();
                $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();
                $query->whereBetween('created_at', [$fecha_inicial, $fecha_fin]);
            }
        } elseif (empty($fecha_inicial) && !empty($fecha_fin)) {
            $fecha_inicial = Carbon::parse($primerTicket->created_at)->startOfDay();
            $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();
            $query->whereBetween('created_at', [$fecha_inicial, $fecha_fin]);
        }elseif(!empty($fecha_inicial) && empty($fecha_fin)){
            $fecha_inicial = Carbon::parse($fecha_inicial)->startOfDay();
            $fecha_fin = Carbon::parse($fecha_actual)->endOfDay();
            $query->whereBetween('created_at', [$fecha_inicial, $fecha_fin]);
        }

  
        // Filtrado por área si se seleccionó una
        if ($area_id && $area_id !== 'all') {
            $query->where('area_id', $area_id);
        }
    
        // Filtrado por estado si se seleccionó uno
        if ($estado_id && $estado_id !== 'all') {
            $query->where('estado_id', $estado_id);
        }
    
        // Resultados
        $tickets = $query->get();
    
        return view('myViews.Admin.reporte.ticketsFiltrados', compact('tickets', 'areas', 'estados', 'primerTicket'));
    }

    public function reporteCompletoPDF(){

        $usuario= Auth::user();
        
        $reporte= new Reporte();
        $reporte->user_id =  $usuario->id;
        $reporte->save();

         // Obtener los tickets
        $tickets = Ticket::with(['user', 'area', 'prioridad', 'estado'])->get();

        // Generar el PDF
        $pdf = PDF::loadView('myViews.Admin.reporte.reporteCompleto', ['tickets' => $tickets, 'idReporte' => $reporte->id]);

        // Devolver el PDF para su descarga
        return $pdf->download('reporte-ticketsCompleto.pdf');
    }


    public function reporteFiltradoPDF(Request $request){
        $usuario = Auth::user();
        $reporte = new Reporte();
        $reporte->user_id = $usuario->id;
        $reporte->save();
    
        // Obtener todos los parámetros del request
        $fecha_inicial = $request->input('fecha_inicial');
        $fecha_fin = $request->input('fecha_fin');
        $area_id = $request->input('area');
        $estado_id = $request->input('estado');
    
        // Guardar datos en sesión
        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_fin' => $fecha_fin]);
        session(['area_id' => $area_id]);
        session(['estado_id' => $estado_id]);

        $primerTicket = Ticket::orderBy('created_at', 'asc')->first();
        $fecha_inicial = $request->input('fecha_inicial');
        $fecha_actual = Carbon::now();
    
        // Construir la consulta base
        $tickets = Ticket::with(['user', 'area', 'prioridad', 'estado'])
            ->when($area_id && $area_id !== 'all', fn($q) => $q->where('area_id', $area_id)) // Filtrar por área si no es 'all'
            ->when($estado_id && $estado_id !== 'all', fn($q) => $q->where('estado_id', $estado_id)); // Filtrar por estado si no es 'all'
    
        // Manejar los filtros de fecha
        if (!empty($fecha_inicial) && !empty($fecha_fin)) {
            if ($fecha_inicial == $fecha_fin) {
                $tickets=$tickets->whereDate('created_at', $fecha_inicial);
            } else {
                $fecha_inicial = Carbon::parse($fecha_inicial)->startOfDay();
                $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();
                $tickets=$tickets->whereBetween('created_at', [$fecha_inicial, $fecha_fin]);
            }
        } elseif (empty($fecha_inicial) && !empty($fecha_fin)) {
            $fecha_inicial = Carbon::parse($primerTicket->created_at)->startOfDay();
            $fecha_fin = Carbon::parse($fecha_fin)->endOfDay();
            $tickets=$tickets->whereBetween('created_at', [$fecha_inicial, $fecha_fin]);
        }elseif(!empty($fecha_inicial) && empty($fecha_fin)){
            $fecha_inicial = Carbon::parse($fecha_inicial)->startOfDay();
            $fecha_fin = Carbon::parse($fecha_actual)->endOfDay();
            $tickets=$tickets->whereBetween('created_at', [$fecha_inicial, $fecha_fin]);
        }
    
        // Ejecutar la consulta
        $tickets = $tickets->get();
    
        $primerTicket = Ticket::orderBy('created_at', 'asc')->first();
    
        // Generar el PDF
        $pdf = PDF::loadView('myViews.Admin.reporte.reporteFiltrado', [
            'tickets' => $tickets,
            'fecha_inicial' => $fecha_inicial,
            'fecha_fin' => $fecha_fin,
            'area_id' => $area_id,
            'estado_id' => $estado_id,
            'idReporte' => $reporte->id,
            'fecha_actual' => Carbon::now(),
            'primerTicket'=> $primerTicket
        ]);
    
        return $pdf->download('reporte-ticketsFiltrados.pdf');
    }
}
