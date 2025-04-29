<?php

namespace App\Http\Controllers\Admin\Grafico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use App\Models\Ticket;
use App\Models\Calificacion;

class GraficoController extends Controller
{

    public function __construct(){

        $this->middleware('can:indexGrafico');
    
    }

    public function index(){
        return view('myViews.Admin.grafico.index');
    }

    public function graficoCSAT(){

        $areas = Area::all();
        $primerTicket = Ticket::first();
        $fecha_inicial = $primerTicket->created_at;
        $fecha_final = Carbon::now();

        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_final' => $fecha_final]);

        $califTicketAll = [];

        // Inicializa el array para cada área
        foreach ($areas as $area) {
            $califTicketAll[$area->id] = []; // Asegúrate de que cada área tenga un array vacío
        }

        foreach ($areas as $area) {
            $ticketsPorArea = Ticket::where('area_id', $area->id)
                ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
                ->has('calificaciones')
                ->get();

            foreach ($ticketsPorArea as $ticket) {
                $calificacionesPorTicket = Calificacion::where('ticket_id', $ticket->id)->get();

                // Filtrar calificaciones donde la calificación sea "totalmente satisfecho" o "satisfecho"
                $calificacionesFiltradas = $calificacionesPorTicket->filter(function ($calificacion) {
                    return isset($calificacion->nivel_satisfaccion)
                        && in_array($calificacion->nivel_satisfaccion, ['Totalmente satisfecho', 'satisfecho']);
                });

                // Agrupar las calificaciones filtradas por area_id del ticket actual
                foreach ($calificacionesFiltradas as $calificacion) {
                    $califTicketAll[$ticket->area_id][] = $calificacion;
                }
            }
        }

        // Convertir el array asociativo a una colección para facilitar el manejo
        $califTicketAll = collect($califTicketAll);
        $datosDrilldown = [];
        $ticketsAreaArray=[];

        foreach($califTicketAll as $claveArea => $valorArea) {

            $ticketsAreaAll = Ticket::where('area_id', $claveArea)->get();
            $ticketsAreaArray[] = $ticketsAreaAll;

            // buscar el área
            $area = Area::find($claveArea);
        
            // Obtener todos los tickets calificados en el área
            $ticketsAreaCalif = Ticket::where('area_id', $claveArea)->has('calificaciones')->get();
            
            // Contar el total de tickets calificados
            $cantidadTicketsCalificados = count($ticketsAreaCalif);
           
            // Filtrar calificaciones "satisfecho" o "totalmente satisfecho"
            $calificacionesFiltradas = Calificacion::whereIn('ticket_id', $ticketsAreaCalif->pluck('id'))
                ->whereIn('nivel_satisfaccion', ['Totalmente satisfecho', 'satisfecho'])
                ->get();
        
            // Contar las calificaciones filtradas
            $cantidadCalificacionesFiltradas = count($calificacionesFiltradas);
        
            // Calcular el porcentaje
            $porcentaje = $cantidadTicketsCalificados > 0 ? ($cantidadCalificacionesFiltradas / $cantidadTicketsCalificados) * 100 : 0;

            // Datos para el gráfico (Área y % de satisfacción)
            $datosGrafico[] = ['name' => $area->nombre, 
                                'y' => floatval($porcentaje), 
                                'drilldown' => $area->nombre, 
                                'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                                'fecha_final' => Carbon::parse($fecha_final)->format('d/m/Y')
                            ];
        
            // Arreglo para los datos de drilldown por área
            $datosDrilldown[$claveArea] = ['name' => $area->nombre, 'id' => $area->nombre, 'data' => []];
        
            $areaNombre = $area->nombre;

            $tecnicosArea = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Técnico de soporte', 'Jefe de área', 'Administrador']);
            })->whereHas('areas', function ($query) use ($areaNombre) {
                $query->where('nombre', $areaNombre);
            })->get()->filter(function ($tecnico) use ($area) {
                return Ticket::where('asignado_a', $tecnico->name)
                    ->where('area_id', $area->id)
                    ->exists();
            });
            
            // // Combinar los técnicos y los administradores que cumplen la condición
            // $tecnicosArea = $tecnicosArea->merge($administradoresConTickets);
            
            foreach ($tecnicosArea as $tecnico) {
                // Verificamos si el técnico tiene tickets asignados
                $ticketsTecnico = Ticket::where('area_id', $area->id)
                    ->where('asignado_a', $tecnico->name)
                    ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
                    ->has('calificaciones')
                    ->get();
            
                // Contamos la cantidad total de tickets calificados asignados al técnico
                $cantidadTicketsCalificados = $ticketsTecnico->count();
            
                $calificacionesValidas = [];
                foreach ($ticketsTecnico as $ticketTec) {
                    $calificacionesSatisf = Calificacion::where('ticket_id', $ticketTec->id)
                        ->whereIn('nivel_satisfaccion', ['Totalmente satisfecho', 'satisfecho'])
                        ->get();
            
                    if ($calificacionesSatisf->isNotEmpty()) {
                        $calificacionesValidas[] = $calificacionesSatisf;
                    }
                }
            
                // Contamos las calificaciones válidas
                $cantidadCalificacionesValidas = count($calificacionesValidas);
            
                // Calculamos el porcentaje solo si hay tickets calificados
                $porcentajeTicketTecnico = $cantidadTicketsCalificados > 0 ? 
                    ($cantidadCalificacionesValidas / $cantidadTicketsCalificados) * 100 : 0;
            
                $AllticketsTecnico = Ticket::where('area_id', $area->id)
                ->where('asignado_a', $tecnico->name)
                ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
                ->get();

                $cantidadAllticketsTecnico= count($AllticketsTecnico);
               
                    // Agregamos los datos al array correspondiente
                    $datosDrilldown[$claveArea]['data'][] = [
                        'name' => $tecnico->name,
                        'y' => $porcentajeTicketTecnico,
                        'AllTicketTecnico' => $cantidadAllticketsTecnico,
                        'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                        'fecha_final' => Carbon::parse($fecha_final)->format('d/m/Y')
                    ];
                
            }

        }


        // $jsonData = json_encode($datosDrilldown, JSON_PRETTY_PRINT);
        // dd($jsonData);

        // *utilizamos array_values($datosDrilldown) para conviertir  el array asociativo de $datosDrilldown en un array simple,
        // *lo cual es más adecuado para ser utilizado directamente en la vista como series.
        return view('myViews.Admin.grafico.csat', ['data' => json_encode($datosGrafico), 'seriesDrilldown' => json_encode(array_values($datosDrilldown))]);
    }


    public function graficoCSAT_Filtrado(Request $request){
         // CSAT DE LAS ÁREAS 
         $request->validate([
            'fecha_inicial' => 'required|date',
            'fecha_final' => 'required|date',
        ]);

        $fecha_inicial = $request->input('fecha_inicial');
        $fecha_final = $request->input('fecha_final');

        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_final' => $fecha_final]);

        $areas = Area::all();
        $datosGrafico = [];
        $datosDrilldown = [];

        $califTicketAll = [];

        // Inicializa el array para cada área
        foreach ($areas as $area) {
            $califTicketAll[$area->id] = []; // Asegúrate de que cada área tenga un array vacío
        }

        foreach ($areas as $area) {

            if($fecha_inicial === $fecha_final){
                $ticketsPorArea = Ticket::where('area_id', $area->id)
                ->whereHas('calificaciones', function($query) use ($fecha_final) {
                    $query->whereDate('created_at', $fecha_final);
                })->get();
           
            }else{
    
                $ticketsPorArea = Ticket::where('area_id', $area->id)
                ->whereHas('calificaciones', function($query) use ($fecha_inicial, $fecha_final) {
                    $query->whereBetween('created_at', [$fecha_inicial, $fecha_final]);
                })->get();
            }
           

            foreach ($ticketsPorArea as $ticket) {
                $calificacionesPorTicket = Calificacion::where('ticket_id', $ticket->id)->get();

                // Filtrar calificaciones donde la calificación sea "totalmente satisfecho" o "satisfecho"
                $calificacionesFiltradas = $calificacionesPorTicket->filter(function ($calificacion) {
                    return isset($calificacion->nivel_satisfaccion)
                        && in_array($calificacion->nivel_satisfaccion, ['Totalmente satisfecho', 'satisfecho']);
                });

                // Agrupar las calificaciones filtradas por area_id del ticket actual
                foreach ($calificacionesFiltradas as $calificacion) {
                    $califTicketAll[$ticket->area_id][] = $calificacion;
                }
            }
        }

        // Convertir el array asociativo a una colección para facilitar el manejo
        $califTicketAll = collect($califTicketAll);
        $ticketsAreaArray=[];
 
        foreach($califTicketAll as $claveArea => $valorArea) {
 
             $ticketsAreaAll = Ticket::where('area_id', $claveArea)->get();
             $ticketsAreaArray[] = $ticketsAreaAll;
 
             // buscar el área
             $area = Area::find($claveArea);
         
             // Obtener todos los tickets calificados en el área
             $ticketsAreaCalif = Ticket::where('area_id', $claveArea)->has('calificaciones')->get();
             
             // Contar el total de tickets calificados
             $cantidadTicketsCalificados = count($ticketsAreaCalif);
            
             // Filtrar calificaciones "satisfecho" o "totalmente satisfecho"
             if($fecha_inicial === $fecha_final){
                $calificacionesFiltradas = Calificacion::whereIn('ticket_id', $ticketsAreaCalif->pluck('id'))
                ->whereIn('nivel_satisfaccion', ['Totalmente satisfecho', 'satisfecho'])
                ->whereDate('created_at', $fecha_final)
                ->get();
             }else{
                $calificacionesFiltradas = Calificacion::whereIn('ticket_id', $ticketsAreaCalif->pluck('id'))
                ->whereIn('nivel_satisfaccion', ['Totalmente satisfecho', 'satisfecho'])
                ->whereBetween('created_at', [$fecha_inicial, $fecha_final]) // Filtra las calificaciones entre las fechas
                ->get();
             }
           
         
             // Contar las calificaciones filtradas
             $cantidadCalificacionesFiltradas = count($calificacionesFiltradas);
         
             // Calcular el porcentaje
             $porcentaje = $cantidadTicketsCalificados > 0 ? ($cantidadCalificacionesFiltradas / $cantidadTicketsCalificados) * 100 : 0;
 
             // Datos para el gráfico (Área y % de satisfacción)
             $datosGrafico[] = ['name' => $area->nombre, 
                                'y' => floatval($porcentaje), 
                                'drilldown' => $area->nombre,
                                'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                                'fecha_final' => Carbon::parse($fecha_final)->format('d/m/Y')
                            ];

             // Arreglo para los datos de drilldown por área
             $datosDrilldown[$claveArea] = ['name' => $area->nombre, 'id' => $area->nombre, 'data' => []];
         
             $areaNombre = $area->nombre;
 
             $tecnicosArea = User::whereHas('roles', function ($query) {
                 $query->whereIn('name', ['Técnico de soporte', 'Jefe de área', 'Administrador']);
             })->whereHas('areas', function ($query) use ($areaNombre) {
                 $query->where('nombre', $areaNombre);
             })->get()->filter(function ($tecnico) use ($area) {
                 return Ticket::where('asignado_a', $tecnico->name)
                     ->where('area_id', $area->id)
                     ->exists();
             });

             
             // // Combinar los técnicos y los administradores que cumplen la condición
             // $tecnicosArea = $tecnicosArea->merge($administradoresConTickets);
             
            foreach ($tecnicosArea as $tecnico) {
                 // Verificamos si el técnico tiene tickets asignados
                if($fecha_inicial === $fecha_final){
                    $ticketsTecnico = Ticket::where('area_id', $area->id)
                    ->where('asignado_a', $tecnico->name)
                    ->whereHas('calificaciones', function($query) use ($fecha_final) {
                        $query->whereDate('created_at', $fecha_final);
                    })->get();
                   
                }else{
                    $ticketsTecnico = Ticket::where('area_id', $area->id)
                    ->where('asignado_a', $tecnico->name)
                    ->whereHas('calificaciones', function($query) use ($fecha_inicial, $fecha_final) {
                        $query->whereBetween('created_at', [$fecha_inicial, $fecha_final]); // Filtra las calificaciones entre las fechas
                    })->get();
                }

                
                 // Contamos la cantidad total de tickets calificados asignados al técnico
                 $cantidadTicketsCalificados = $ticketsTecnico->count();
             
                 $calificacionesValidas = [];
                 foreach ($ticketsTecnico as $ticketTec) {
                     $calificacionesSatisf = Calificacion::where('ticket_id', $ticketTec->id)
                         ->whereIn('nivel_satisfaccion', ['Totalmente satisfecho', 'satisfecho'])
                         ->get();
             
                     if ($calificacionesSatisf->isNotEmpty()) {
                         $calificacionesValidas[] = $calificacionesSatisf;
                     }
                 }
             
                 // Contamos las calificaciones válidas
                 $cantidadCalificacionesValidas = count($calificacionesValidas);
             
                 // Calculamos el porcentaje solo si hay tickets calificados
                 $porcentajeTicketTecnico = $cantidadTicketsCalificados > 0 ? 
                     ($cantidadCalificacionesValidas / $cantidadTicketsCalificados) * 100 : 0;
             
                 $AllticketsTecnico = Ticket::where('area_id', $area->id)
                 ->where('asignado_a', $tecnico->name)
                 ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
                 ->get();
 
                 $cantidadAllticketsTecnico= count($AllticketsTecnico);
                
                     // Agregamos los datos al array correspondiente
                     $datosDrilldown[$claveArea]['data'][] = [
                         'name' => $tecnico->name,
                         'y' =>$porcentajeTicketTecnico,
                         'AllTicketTecnico' => $cantidadAllticketsTecnico,
                         'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                         'fecha_final' => Carbon::parse($fecha_final)->format('d/m/Y')
                     ];
                 
            }
        }

        return view('myViews.Admin.grafico.csat', ['data' => json_encode($datosGrafico), 'seriesDrilldown' => json_encode(array_values($datosDrilldown))]);

    }

    public function graficoMTTR(Request $request){

        $areas = Area::all();
        $primerTicket=Ticket::first();
        $fecha_inicial= $primerTicket->created_at;
        $fecha_final=Carbon::now();

        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_final' => $fecha_final]);

        foreach($areas as $area){

            $tickets = Ticket::where('area_id', $area->id)->whereHas('estado', function($query) {
                $query->where('nombre', 'Cerrado');
            }) ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])->get();
     

            $totalTiempo = 0;

            foreach ($tickets as $ticket) {
                // diferencia en horas entre updated_at y created_at
                $diferencia = $ticket->created_at->diffInHours($ticket->updated_at);
                $totalTiempo += $diferencia; // total de la Suma de cada diferencia 
            }

            $cantidadTickets=count($tickets);
           
            if ($cantidadTickets > 0) {
                $mttr = $totalTiempo / $cantidadTickets; 
            } else {
                $mttr = 0; 
            }

            // Datos para el gráfico (Área n)
            $datosGrafico[] = ['name' => $area->nombre, 
                                'y' => floatval($mttr), 
                                'drilldown' => $area->nombre, 
                                'cantidadTickets' => $cantidadTickets, 
                                'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                                'fecha_final'=> Carbon::parse($fecha_final)->format('d/m/Y')
                            ];
        
            // Arreglo para los datos de drilldown por área
            $datosDrilldown[$area->id] = ['name' => $area->nombre, 'id' => $area->nombre, 'data' => []];

            // MTTR DE LOS TÉCNICOS DE LAS ÁREAS 
            $areaNombre = $area->nombre;
            $totalTiempoTecnico= 0;
           
            $tecnicosArea = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Técnico de soporte', 'Jefe de área', 'Administrador']);
            })
            ->whereHas('areas', function ($query) use ($areaNombre) {
                $query->where('nombre', $areaNombre);
            })
            ->get()
            ->filter(function ($tecnico) use ($area) {
                // Verificar si el técnico tiene tickets asignados
                $tieneTicketAsignado = Ticket::where('asignado_a', $tecnico->name)
                    ->where('area_id', $area->id)
                    ->exists();
        
                // Mostrar solo Administradores si tienen tickets asignados
                if ($tecnico->hasRole('Administrador')) {
                    return $tieneTicketAsignado;
                }
        
                // Incluir otros roles sin necesidad de tickets asignados
                return !$tecnico->hasRole('Administrador') || !$tieneTicketAsignado;
            });
           
            foreach($tecnicosArea as $tecnico){
                $totalTiempoTecnico = 0;

                $ticketsTecnico = Ticket::where('area_id', $area->id)->where('asignado_a', $tecnico->name)->whereHas('estado', function($query) {
                    $query->where('nombre', 'Cerrado');
                }) ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])->get();

               
                foreach($ticketsTecnico as $ticket){

                    // diferencia en segundos entre updated_at y created_at
                    $diferenciaTiempoTecnico = $ticket->created_at->diffInHours($ticket->updated_at);
                    $totalTiempoTecnico += $diferenciaTiempoTecnico; // total de la Suma de cada la diferencia  
                }

                $cantidadTicketsTecnico=count($ticketsTecnico);
                    
            
                if ($cantidadTicketsTecnico > 0){
                    
                    $mttrTecnico = $totalTiempoTecnico / $cantidadTicketsTecnico;
                }else{
                    $mttrTecnico = 0 ; 
                }

                $datosDrilldown[$area->id]['data'][] = [
                    'name' => $tecnico->name,
                    'y' => floatval($mttrTecnico),
                    'cantidadTickets' => $cantidadTicketsTecnico,
                    'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                    'fecha_final' => Carbon::parse($fecha_final)->format('d/m/Y')
                ];
            
            }
         
        }

        return view('myViews.Admin.grafico.mttr', ['data' => json_encode($datosGrafico), 'seriesDrilldown' => json_encode(array_values($datosDrilldown))]);
    }

    public function graficoMTTR_Filtrado(Request $request){
        // MTTR DE LAS ÁREAS 
        $request->validate([
            'fecha_inicial' => 'required|date',
            'fecha_final' => 'required|date',
        ]);

        $fecha_inicial = $request->input('fecha_inicial');
        $fecha_final = $request->input('fecha_final');

        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_final' => $fecha_final]);

        $areas = Area::all();
        $datosGrafico = [];
        $datosDrilldown = [];

        foreach($areas as $area){


            if($fecha_inicial === $fecha_final){

                $tickets = Ticket::where('area_id', $area->id)->whereHas('estado', function($query) {
                    $query->where('nombre', 'Cerrado');
                }) ->whereDate('updated_at', $fecha_final)->get();

            }else{
                $tickets = Ticket::where('area_id', $area->id)->whereHas('estado', function($query) {
                    $query->where('nombre', 'Cerrado');
                }) ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])->get();
            }
       

            $totalTiempo = 0;

            foreach ($tickets as $ticket) {
                // diferencia en horas entre updated_at y created_at
                $diferencia = $ticket->created_at->diffInHours($ticket->updated_at);
                $totalTiempo += $diferencia; // total de la Suma de cada la diferencia 
            }
            $cantidadTickets=count($tickets);
           
            if ($cantidadTickets > 0) {
                $mttr = $totalTiempo / $cantidadTickets; 
            } else {
                $mttr = 0; 
            }


            // Datos para el gráfico (Áreas)
            $datosGrafico[] = ['name' => $area->nombre, 
                                'y' => floatval($mttr), 
                                'drilldown' => $area->nombre, 
                                'cantidadTickets' => $cantidadTickets,
                                'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                                'fecha_final'=> Carbon::parse($fecha_final)->format('d/m/Y')
                            ];
        
            // Arreglo para los datos de drilldown por área
            $datosDrilldown[$area->id] = ['name' => $area->nombre, 'id' => $area->nombre, 'data' => []];


            // MTTR DE LOS TÉCNICOS DE LAS ÁREAS 
            $areaNombre = $area->nombre;
            $totalTiempoTecnico= 0;
           

            $tecnicosArea = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Técnico de soporte', 'Jefe de área', 'Administrador']);
            })
            ->whereHas('areas', function ($query) use ($areaNombre) {
                $query->where('nombre', $areaNombre);
            })
            ->get()
            ->filter(function ($tecnico) use ($area) {
                // Verificar si el técnico tiene tickets asignados
                $tieneTicketAsignado = Ticket::where('asignado_a', $tecnico->name)
                    ->where('area_id', $area->id)
                    ->exists();
        
                // Mostrar solo Administradores si tienen tickets asignados
                if ($tecnico->hasRole('Administrador')) {
                    return $tieneTicketAsignado;
                }
        
                // Incluir otros roles sin necesidad de tickets asignados
                return !$tecnico->hasRole('Administrador') || !$tieneTicketAsignado;
            });

            foreach($tecnicosArea as $tecnico){
                $totalTiempoTecnico = 0;
                
                if($fecha_inicial === $fecha_final){
                
                    $ticketsTecnico = Ticket::where('area_id', $area->id)->where('asignado_a', $tecnico->name)
                    ->whereHas('estado', function($query) {
                        $query->where('nombre', 'Cerrado');
                    }) ->whereDate('updated_at', $fecha_final)->get();
    
                }else{
                    $ticketsTecnico = Ticket::where('area_id', $area->id)->where('asignado_a', $tecnico->name)
                    ->whereHas('estado', function($query) {
                        $query->where('nombre', 'Cerrado');
                    }) ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])->get();
                }
              
               
                foreach($ticketsTecnico as $ticket){

                    // diferencia en segundos entre updated_at y created_at
                    $diferenciaTiempoTecnico = $ticket->created_at->diffInHours($ticket->updated_at);
                    $totalTiempoTecnico += $diferenciaTiempoTecnico; // total de la Suma de cada la diferencia  
                }

                $cantidadTicketsTecnico=count($ticketsTecnico);
           
                if ($cantidadTicketsTecnico > 0) {
                    $mttrTecnico = $totalTiempoTecnico / $cantidadTicketsTecnico; 
               
                } else {
                    $mttrTecnico = 0; 
                }
    
                $datosDrilldown[$area->id]['data'][] = [
                    'name' => $tecnico->name,
                    'y' => floatval($mttrTecnico),
                    'cantidadTickets' => $cantidadTicketsTecnico,
                    'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                    'fecha_final' => Carbon::parse($fecha_final)->format('d/m/Y')
                ];
    
            }

        }

        return view('myViews.Admin.grafico.mttr', ['data' => json_encode($datosGrafico), 'seriesDrilldown' => json_encode(array_values($datosDrilldown))]);

    }


    public function graficoFCR(){

        // FCR POR ÁREAS
        $areas = Area::all();
        $primerTicket=Ticket::first();
        $fecha_inicial= $primerTicket->created_at;
        $fecha_final=Carbon::now();

        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_final' => $fecha_final]);

        foreach($areas as $area){

            // Tickets del area resueltos en el primer contacto (durante el periodo de fechas)
            $tickets_areaFCR= Ticket::where('area_id', $area->id)
            ->whereHas('estado', function($query) {
                $query->where('nombre', 'Cerrado');
            })->whereRaw('DATE(created_at) = DATE(updated_at)')
            ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
            ->get();

            // Cantidad de tickets del area resueltos en el primer contacto (durante el periodo de fechas)
            $cantidadTicketsResueltos= count($tickets_areaFCR);

            // Tickets reportados (durante el mismo periodo de fechas que $tickets_areaFCR)
            $ticketReportados=Ticket::where('area_id', $area->id)
            ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
            ->get();

            // cantidad de tickets reportados (durante el mismo periodo de fechas que $tickets_areaFCR)
            $cantidadTicketsReportados=count($ticketReportados);

            
            // Formula para calcular FCR por areas
            if($cantidadTicketsResueltos > 0 && $cantidadTicketsReportados > 0){
                $fcrArea= ($cantidadTicketsResueltos / $cantidadTicketsReportados) * 100;
            }else{
                $fcrArea=0;
            }

            // Datos para el gráfico (Áreas)
            $datosGrafico[] = ['name' => $area->nombre, 
                               'y' => floatval($fcrArea), 
                               'drilldown' => $area->nombre, 
                               'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                               'fecha_final'=> Carbon::parse($fecha_final)->format('d/m/Y')
                            ];

            // Arreglo para los datos de drilldown por área
            $datosDrilldown[$area->id] = ['name' => $area->nombre, 'id' => $area->nombre, 'data' => []];


            // FCR POR TECNICOS DE ÁREAS
            $areaNombre = $area->nombre;

            $tecnicosArea = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Técnico de soporte', 'Jefe de área', 'Administrador']);
            })
            ->whereHas('areas', function ($query) use ($areaNombre) {
                $query->where('nombre', $areaNombre);
            })
            ->get()
            ->filter(function ($tecnico) use ($area) {
                // Verificar si el técnico tiene tickets asignados
                $tieneTicketAsignado = Ticket::where('asignado_a', $tecnico->name)
                    ->where('area_id', $area->id)
                    ->exists();
        
                // Mostrar solo Administradores si tienen tickets asignados
                if ($tecnico->hasRole('Administrador')) {
                    return $tieneTicketAsignado;
                }
        
                // Incluir otros roles sin necesidad de tickets asignados
                return !$tecnico->hasRole('Administrador') || !$tieneTicketAsignado;
            });

           foreach($tecnicosArea as $tecnico){
                // Tickets respondido por el tecnico  en el primer contacto (durante un periodo de fechas)
                $ticketsTecnico_FCR = Ticket::where('area_id', $area->id)
                ->where('asignado_a', $tecnico->name)->whereHas('estado', function($query) {
                    $query->where('nombre', 'Cerrado');
                }) ->whereRaw('DATE(created_at) = DATE(updated_at)')
                ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])->get();

                // cantidad de tickets respondido por el tecnico  en el primer contacto 
                // (durante un periodo de fechas)
                $cantidadTicketsTecnico_FCR= count($ticketsTecnico_FCR);

                // Tickets reportados asignados al tecnico (durante el mismo periodo de fechas que $tickets_areaFCR)
                $ticketReportadosTecnico=Ticket::where('area_id', $area->id)
                ->where('asignado_a', $tecnico->name)
                ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
                ->get();

                // cantidad de tickets reportados asignados al tecnico (durante el mismo periodo de fechas que $tickets_areaFCR)
                $cantidadTicketsReportadosTecnico=count($ticketReportadosTecnico);


                 if ($cantidadTicketsTecnico_FCR > 0 && $cantidadTicketsReportadosTecnico > 0){
                    
                    $fcrTecnico = ($cantidadTicketsTecnico_FCR / $cantidadTicketsReportadosTecnico) * 100;
                }else{
                    $fcrTecnico = 0 ; 
                }

                $datosDrilldown[$area->id]['data'][] = [
                    'name' => $tecnico->name,
                    'y' => floatval($fcrTecnico),
                    'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                    'fecha_final' => Carbon::parse($fecha_final)->format('d/m/Y')
                ];

           }
        
        }

        return view('myViews.Admin.grafico.fcr', ['data' => json_encode($datosGrafico), 'seriesDrilldown' => json_encode(array_values($datosDrilldown))]);
    }

    public function graficoFCR_Filtrado(Request $request){

        // FCR DE LAS ÁREAS 
        $request->validate([
            'fecha_inicial' => 'required|date',
            'fecha_final' => 'required|date',
        ]);

        $fecha_inicial = $request->input('fecha_inicial');
        $fecha_final = $request->input('fecha_final');

        session(['fecha_inicial' => $fecha_inicial]);
        session(['fecha_final' => $fecha_final]);

        $areas = Area::all();
   
        foreach($areas as $area){

            // Tickets del area resueltos en el primer contacto (durante el periodo de fechas)
            if($fecha_inicial === $fecha_final){
              
                $tickets_areaFCR= Ticket::where('area_id', $area->id)
                ->whereHas('estado', function($query) {
                $query->where('nombre', 'Cerrado');
                 })->whereRaw('DATE(created_at) = DATE(updated_at)')
                ->whereDate('updated_at', $fecha_final)
                ->get();

            }else{

                $tickets_areaFCR= Ticket::where('area_id', $area->id)
                ->whereHas('estado', function($query) {
                    $query->where('nombre', 'Cerrado');
                })->whereRaw('DATE(created_at) = DATE(updated_at)')
                ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
                ->get();
            }

            // Cantidad de tickets del area resueltos en el primer contacto (durante el periodo de fechas)
            $cantidadTicketsResueltos= count($tickets_areaFCR);

            // Tickets reportados (durante el mismo periodo de fechas que $tickets_areaFCR)
            if($fecha_inicial === $fecha_final){
                $ticketReportados=Ticket::where('area_id', $area->id)
                ->whereDate('created_at', $fecha_final)
                ->get();
            }else{
                $ticketReportados=Ticket::where('area_id', $area->id)
                ->whereBetween('created_at', [$fecha_inicial, $fecha_final])
                ->get();
            }


            // cantidad de tickets reportados (durante el mismo periodo de fechas que $tickets_areaFCR)
            $cantidadTicketsReportados=count($ticketReportados);

            
            // Formula para calcular FCR por areas
            if($cantidadTicketsResueltos > 0 && $cantidadTicketsReportados > 0){
                $fcrArea= ($cantidadTicketsResueltos / $cantidadTicketsReportados) * 100;
            }else{
                $fcrArea=0;
            }

            // Datos para el gráfico (Áreas)
            $datosGrafico[] = ['name' => $area->nombre, 
                               'y' => floatval($fcrArea), 
                               'drilldown' => $area->nombre, 
                               'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                               'fecha_final'=> Carbon::parse($fecha_final)->format('d/m/Y')];

            // Arreglo para los datos de drilldown por área
            $datosDrilldown[$area->id] = ['name' => $area->nombre, 'id' => $area->nombre, 'data' => []];


            // FCR POR TECNICOS DE ÁREAS
            $areaNombre = $area->nombre;

            $tecnicosArea = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Técnico de soporte', 'Jefe de área', 'Administrador']);
            })
            ->whereHas('areas', function ($query) use ($areaNombre) {
                $query->where('nombre', $areaNombre);
            })
            ->get()
            ->filter(function ($tecnico) use ($area) {
                // Verificar si el técnico tiene tickets asignados
                $tieneTicketAsignado = Ticket::where('asignado_a', $tecnico->name)
                    ->where('area_id', $area->id)
                    ->exists();
        
                // Mostrar solo Administradores si tienen tickets asignados
                if ($tecnico->hasRole('Administrador')) {
                    return $tieneTicketAsignado;
                }
        
                // Incluir otros roles sin necesidad de tickets asignados
                return !$tecnico->hasRole('Administrador') || !$tieneTicketAsignado;
            });

            foreach($tecnicosArea as $tecnico){
                // Tickets respondido por el tecnico  en el primer contacto (durante un periodo de fechas)

                if($fecha_inicial === $fecha_final){

                    $ticketsTecnico_FCR = Ticket::where('area_id', $area->id)
                    ->where('asignado_a', $tecnico->name)->whereHas('estado', function($query) {
                        $query->where('nombre', 'Cerrado');
                    }) ->whereRaw('DATE(created_at) = DATE(updated_at)')
                    ->whereDate('updated_at', $fecha_final)->get();

                }else{
                    $ticketsTecnico_FCR = Ticket::where('area_id', $area->id)
                    ->where('asignado_a', $tecnico->name)->whereHas('estado', function($query) {
                        $query->where('nombre', 'Cerrado');
                    }) ->whereRaw('DATE(created_at) = DATE(updated_at)')
                    ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])->get();
                }

                // cantidad de tickets respondido por el tecnico  en el primer contacto 
                // (durante un periodo de fechas)
                $cantidadTicketsTecnico_FCR= count($ticketsTecnico_FCR);

                // Tickets reportados asignados al tecnico (durante el mismo periodo de fechas que $tickets_areaFCR)
                if($fecha_inicial === $fecha_final){
                    $ticketReportadosTecnico=Ticket::where('area_id', $area->id)
                    ->where('asignado_a', $tecnico->name)
                    ->whereDate('created_at', $fecha_final)
                    ->get();
                }else{
                    $ticketReportadosTecnico=Ticket::where('area_id', $area->id)
                    ->where('asignado_a', $tecnico->name)
                    ->whereBetween('updated_at', [$fecha_inicial, $fecha_final])
                    ->get();
                }

                // cantidad de tickets reportados asignados al tecnico (durante el mismo periodo de fechas que $tickets_areaFCR)
                $cantidadTicketsReportadosTecnico=count($ticketReportadosTecnico);


                if ($cantidadTicketsTecnico_FCR > 0 && $cantidadTicketsReportadosTecnico > 0){
                    
                    $fcrTecnico = ($cantidadTicketsTecnico_FCR / $cantidadTicketsReportadosTecnico) * 100;
                }else{
                    $fcrTecnico = 0 ; 
                }

                $datosDrilldown[$area->id]['data'][] = [
                    'name' => $tecnico->name,
                    'y' => floatval($fcrTecnico),
                    'fecha_inicial' => Carbon::parse($fecha_inicial)->format('d/m/Y'), 
                    'fecha_final' => Carbon::parse($fecha_final)->format('d/m/Y')
                ];

            }
        }

        return view('myViews.Admin.grafico.fcr', ['data' => json_encode($datosGrafico), 'seriesDrilldown' => json_encode(array_values($datosDrilldown))]);
           
    }

}
