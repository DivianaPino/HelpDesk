<?php

namespace App\Http\Controllers\Admin\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Reporte;
use Carbon\Carbon;
use PDF;

class ReporteController extends Controller
{
    public function reporte(){
        $tickets=Ticket::with(['user', 'clasificacion', 'prioridad', 'estado'])->get();
        return view('myViews.Admin.reporte.reporte')->with('tickets', $tickets);
    }

    public function reporteFiltrados(Request $request)
    {

        $fecha_inicial = $request->input('fecha_inicial');
  
        $fecha_fin = $request->input('fecha_fin');

        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_fin' => $fecha_fin]);
        

         // Si no se proporcionan fechas, muestra todos los tickets
        if (empty($fecha_inicial) || empty($fecha_fin)) {
          
            $tickets = Ticket::with(['user', 'clasificacion', 'prioridad', 'estado'])->get();

        // Si las fechas son iguales, filtra los tickets que fueron creados exactamente en esa fecha
        }elseif ($fecha_inicial == $fecha_fin) {
            
            $tickets = Ticket::whereDate('created_at', $fecha_inicial)
                            ->with(['user', 'clasificacion', 'prioridad', 'estado'])
                            ->get();

        // Si las fechas no son iguales, filtra los tickets que fueron creados entre esas dos fechas    
        }elseif($fecha_inicial != $fecha_fin) {
           
            $tickets = Ticket::whereBetween('created_at', [$fecha_inicial, $fecha_fin])
                            ->with(['user', 'clasificacion', 'prioridad', 'estado'])
                            ->get();
        }

        return view('myViews.Admin.reporte.ticketsFiltrados', compact('tickets'));

    }

    public function reporteCompletoPDF(){

        $usuario= Auth::user();
        
        $reporte= new Reporte();
        $reporte->user_id =  $usuario->id;
        $reporte->save();

         // Obtener los tickets
        $tickets = Ticket::with(['user', 'clasificacion', 'prioridad', 'estado'])->get();

        // Generar el PDF
        $pdf = PDF::loadView('myViews.Admin.reporte.reporteCompleto', ['tickets' => $tickets, 'idReporte' => $reporte->id]);

        // Devolver el PDF para su descarga
        return $pdf->download('reporte-ticketsCompleto.pdf');
    }


    public function reporteRangoPDF(Request $request){

        $usuario= Auth::user();
        
        $reporte= new Reporte();
        $reporte->user_id =  $usuario->id;
        $reporte->save();

        $fecha_inicial = $request->input('fecha_inicial');
  
        $fecha_fin = $request->input('fecha_fin');

        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_fin' => $fecha_fin]);
        
        $fecha_actual=Carbon::now();

         // Si no se proporcionan fechas, muestra todos los tickets
        if (empty($fecha_inicial) || empty($fecha_fin)) {
            $tickets = Ticket::with(['user', 'clasificacion', 'prioridad', 'estado'])->get();

        // Si las fechas son iguales, filtra los tickets que fueron creados exactamente en esa fecha
        }elseif ($fecha_inicial == $fecha_fin) {
            
            $tickets = Ticket::whereDate('created_at', $fecha_inicial)
                            ->with(['user', 'clasificacion', 'prioridad', 'estado'])
                            ->get();

        // Si las fechas no son iguales, filtra los tickets que fueron creados entre esas dos fechas    
        }elseif($fecha_inicial != $fecha_fin) {
           
            $tickets = Ticket::whereBetween('created_at', [$fecha_inicial, $fecha_fin])
                            ->with(['user', 'clasificacion', 'prioridad', 'estado'])
                            ->get();
        }

        // Generar el PDF
        $pdf = PDF::loadView('myViews.Admin.reporte.reporteRango', ['tickets' => $tickets, 
                                                                    'fecha_inicial' =>$fecha_inicial,
                                                                    'fecha_fin'=>$fecha_fin, 
                                                                    'idReporte' => $reporte->id,
                                                                    'fecha_actual'=>$fecha_actual]);

        // Devolver el PDF para su descarga
        return $pdf->download('reporte-ticketsRango.pdf');
   }
}
