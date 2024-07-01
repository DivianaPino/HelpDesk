<?php

namespace App\Http\Controllers\Admin\Grafico;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Area;
use App\Models\Ticket;
use App\Models\Comentario;

class GraficoController extends Controller
{
    public function index(){

        $areas = Area::all();
        $comentTicketAll = [];
        $datosGrafico=[];

        foreach ($areas as $area) {
           
            $ticketsPorArea = Ticket::where('clasificacion_id', $area->id)->has('comments')->get();
    
            foreach ($ticketsPorArea as $ticket) {
                $comentariosPorTicket = Comentario::where('ticket_id', $ticket->id)->get();
    
                // Filtrar comentarios donde bool_reabrir es 0
                $comentariosFiltrados = $comentariosPorTicket->filter(function ($comentario) {
                    return $comentario->bool_reabrir == 0;
                });
    
                // Agrupar los comentarios filtrados por clasificacion_id del ticket actual
                if (!isset($comentTicketAll[$ticket->clasificacion_id])) {
                    $comentTicketAll[$ticket->clasificacion_id] = [];
                }
                foreach ($comentariosFiltrados as $comentario) {
                    $comentTicketAll[$ticket->clasificacion_id][] = $comentario;
                }
            }
        }


        // Convertir el array asociativo a una colección para facilitar el manejo
        $comentTicketAll = collect($comentTicketAll);

        $datosDrilldown = [];

        foreach($comentTicketAll as $claveArea => $valorArea) {
            $ticketsAreaAll = Ticket::where('clasificacion_id', $claveArea)->get();
            $ticketsAreaArray[] = $ticketsAreaAll;
        
            // buscar el área
            $area = Area::find($claveArea);
            // contamos los ticket con comentarios satisfactorios que tiene cada area, los cuales estan en un array
            $cantidadComentario = count($valorArea);
        
            foreach($ticketsAreaArray as $ticket) {
                $cantidadTickets = count($ticket);
                $porcentaje = ($cantidadComentario / $cantidadTickets) * 100;
            }
        
            // Datos para el gráfico (Área y % de satisfacción)
            $datosGrafico[] = ['name' => $area->nombre, 'y' => floatval($porcentaje), 'drilldown' => $area->nombre];
          
        
            // Inicializa un nuevo arreglo para los datos de drilldown por área
            $datosDrilldown[$claveArea] = ['name' => $area->nombre, 'id' => $area->nombre, 'data' => []];
        
            $areaNombre= $area->nombre;

            $tecnicosArea = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Jefe de área', 'Técnico de soporte']);
            })->whereHas('areas', function ($query) use ($areaNombre) {
                $query->where('nombre', $areaNombre);
            })->get();
        
            foreach($tecnicosArea as $tecnico) {
                $ticketsTecnico = Ticket::where('clasificacion_id', $area->id)->where('asignado_a', $tecnico->name)->has('comments')->get();
        
                $comentariosValidos = [];
                $nombresTecnicos[] = $tecnico->name;
        
                foreach($ticketsTecnico as $ticketTec) {
                    $comentariosSatisf = Comentario::where('ticket_id', $ticketTec->id)->where('bool_reabrir', false)->get();
                    $comentariosConDatos = $comentariosSatisf->isNotEmpty()? $comentariosSatisf : null;
        
                    if ($comentariosConDatos!== null) {
                        $comentariosValidos[] = $comentariosConDatos;
                    }
                }
        
                $cantidadComentariosValidos = count($comentariosValidos);
                $porcentajeTicketTecnico = ($cantidadComentariosValidos / $cantidadTickets) * 100;
        
                // Agrega los datos del técnico al arreglo correcto basado en la clave de área
                $datosDrilldown[$claveArea]['data'][] = ['name' => $tecnico->name, 'y' => $porcentajeTicketTecnico];
            }
        }
        
     
        // $jsonData = json_encode($datosDrilldown, JSON_PRETTY_PRINT);
        // dd($jsonData);

        // *utilizamos array_values($datosDrilldown) para conviertir  el array asociativo de $datosDrilldown en un array simple,
        // *lo cual es más adecuado para ser utilizado directamente en la vista como series.
        return view('myViews.Admin.grafico.grafico', ['data' => json_encode($datosGrafico), 'seriesDrilldown' => json_encode(array_values($datosDrilldown))]);
    }
}
