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

        $areas = Area::all();
        $califTicketAll = [];
        $datosGrafico=[];

        foreach ($areas as $area) {

            $ticketsPorArea = Ticket::where('area_id', $area->id)->has('calificaciones')->get();
          
            foreach ($ticketsPorArea as $ticket) {
                $calificacionesPorTicket = Calificacion::where('ticket_id', $ticket->id)->get();
    
                // Filtrar calificaciones donde bool_reabrir es 0
                $calificacionesFiltradas = $calificacionesPorTicket->filter(function ($calificacion) {
                    return isset($calificacion->nivel_satisfaccion)
                        && in_array($calificacion->nivel_satisfaccion, ['Totalmente satisfecho', 'satisfecho', 'neutral']);
                });

                
                //Agrupar las calificaciones filtradas por area_id del ticket actual
                if (!isset($califTicketAll[$ticket->area_id])) {
                    $califTicketAll[$ticket->area_id] = [];
                }
                foreach ($calificacionesFiltradas as $calificacion) {
                    $califTicketAll[$ticket->area_id][] = $calificacion;
                }
            }
        }

          
        //Convertir el array asociativo a una colección para facilitar el manejo
        $califTicketAll = collect($califTicketAll);

        $datosDrilldown = [];

        $ticketsAreaArray=[];

        foreach($califTicketAll as $claveArea => $valorArea) {

            $ticketsAreaAll = Ticket::where('area_id', $claveArea)->get();
            $ticketsAreaArray[] = $ticketsAreaAll;

            // buscar el área
            $area = Area::find($claveArea);

            $ticketsAreaCalif = Ticket::where('area_id', $claveArea)->has('calificaciones')->get();
            $cantidadCalificaciones = count( $ticketsAreaCalif);

            // echo $cantidadCalificaciones;

            $porcentajes = [];

            $cantidadTickets = count($ticketsAreaAll);
                
            $porcentaje = ($cantidadCalificaciones / $cantidadTickets) * 100;
        
            // Datos para el gráfico (Área y % de satisfacción)
            $datosGrafico[] = ['name' => $area->nombre, 'y' => floatval($porcentaje), 'drilldown' => $area->nombre];
        
            //arreglo para los datos de drilldown por área
            $datosDrilldown[$claveArea] = ['name' => $area->nombre, 'id' => $area->nombre, 'data' => []];
        
            $areaNombre= $area->nombre;

            $tecnicosArea = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Administrador','Jefe de área', 'Técnico de soporte']);
            })->whereHas('areas', function ($query) use ($areaNombre) {
                $query->where('nombre', $areaNombre);
            })->get();
        
            foreach($tecnicosArea as $tecnico) {
                // Primero verificamos si es administrador y tiene tickets asignados
                $tieneTickets = Ticket::where('asignado_a', $tecnico->name)->exists();
                
                // Solo procesamos si es administrador y tiene tickets asignados, o si no es administrador
                if ($tecnico->hasRole('Administrador') && $tieneTickets || !$tecnico->hasRole('Administrador')) {
                    $ticketsTecnico = Ticket::where('area_id', $area->id)
                        ->where('asignado_a', $tecnico->name)
                        ->has('calificaciones')
                        ->get();
                        
                    $calificacionesValidas = [];
                    foreach($ticketsTecnico as $ticketTec) {
                        $calificacionesSatisf = Calificacion::where('ticket_id', $ticketTec->id)
                            ->whereIn('nivel_satisfaccion', ['Totalmente satisfecho', 'satisfecho', 'neutral'])
                            ->get();
                            
                        $calificacionConDatos = $calificacionesSatisf->isNotEmpty() ? $calificacionesSatisf : null;
                        
                        if ($calificacionConDatos !== null) {
                            $calificacionesValidas[] = $calificacionConDatos;
                        }
                    }
                    
                    $cantidadCalificacionesValidas = count($calificacionesValidas);
                    $porcentajeTicketTecnico = ($cantidadCalificacionesValidas / $cantidadTickets) * 100;
                    
                    // Agregamos los datos al array correspondiente
                    if ($tecnico->hasRole('Administrador')) {
                        $datosDrilldown[$claveArea]['data'][] = [
                            'name' => $tecnico->name,
                            'y' => $porcentajeTicketTecnico
                        ];
                    } else {
                        $datosDrilldown[$claveArea]['data'][] = [
                            'name' => $tecnico->name,
                            'y' => $porcentajeTicketTecnico
                        ];
                    }
                }
            }
        }

        // $jsonData = json_encode($datosDrilldown, JSON_PRETTY_PRINT);
        // dd($jsonData);

        // *utilizamos array_values($datosDrilldown) para conviertir  el array asociativo de $datosDrilldown en un array simple,
        // *lo cual es más adecuado para ser utilizado directamente en la vista como series.
        return view('myViews.Admin.grafico.grafico', ['data' => json_encode($datosGrafico), 'seriesDrilldown' => json_encode(array_values($datosDrilldown))]);
    }
}
