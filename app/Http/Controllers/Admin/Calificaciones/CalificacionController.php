<?php

namespace App\Http\Controllers\Admin\Calificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Calificacion;
use App\Models\Mensaje;
use App\Models\Ticket;

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     public function nivel_satisfaccion($idTicket)
     {
         $ticket = Ticket::find($idTicket); // Ejemplo de cómo podrías obtener la información
         $ultimaCalif=$ticket->ultimaCalificacion;
         $nivelSatisfaccion=$ultimaCalif->nivel_satisfaccion;
         
         return ['nivel' => $nivelSatisfaccion];
     }

     public function calificaciones_ticketCliente($idTicket)
     {
         // Aquí puedes agregar lógica para obtener la información del ticket
         $ticket = Ticket::find($idTicket); // Ejemplo de cómo podrías obtener la información
         $cliente=$ticket->user->name;
         $calificacionesticket=$ticket->calificaciones;

         $usuarioAuth=Auth::user();
         $esTecnico=false;

         if($usuarioAuth->hasAnyRole(['Administrador', 'Jefe de área', 'Técnico de soporte'])){
           $esTecnico=true;
         }

         return view('myViews.Admin.tickets.calificacionesTicket')->with(['calificaciones'=> $calificacionesticket,
                                                                 'idTicket' =>  $idTicket,
                                                                  'cliente' => $cliente,
                                                                  'esTecnico' =>  $esTecnico]);
     }

    //  public function calificacion_ticketCliente($idCalificacion){
    //     return view('myViews.usuarioEst.calificacion');
    //  }



    public function calificaciones(){

        $usuario= Auth::user();
        $allCalificaciones = [];

        if ($usuario->hasRole(['Administrador'])) {
            $calificaciones=Calificacion::all();

            return view('myViews.Admin.tickets.todasCalificaciones', compact('calificaciones'));
            
        }
        elseif($usuario->hasRole(['Jefe de área'])){

            $usuario= Auth::user();
            $areasUsuario=$usuario->areas()->pluck('area_id');
          
            foreach ($areasUsuario as $area) {

                $ticketsArea=Ticket::where('area_id', $area)->get();
       
               
                foreach ($ticketsArea as $ticket){
                    // Obtiene todos los comentarios para el ticket actual y los agrega al array global
                    $ticketCalificaciones = Calificacion::where('ticket_id', $ticket->id)->get();
                
                    $allCalificaciones[] = $ticketCalificaciones;    
                    
                }

            }                
            return view('myViews.jefeArea.calificacionesArea')->with('calificaciones', $allCalificaciones);

        }
        elseif($usuario->hasRole(['Técnico de soporte'])){

            $usuario=Auth::user();
        
            $tickets=Ticket::where('asignado_a', $usuario->name)->get();
     
            foreach($tickets as $ticket){
                 $calificaciones=Calificacion::where('ticket_id', $ticket->id)->get();
                 $allCalificaciones[] = $calificaciones;
            }
     
            return view('myViews.tecnicoSop.misTickets.calificaciones_misTickets')->with('calificaciones', $allCalificaciones);
     
        }
        elseif($usuario->hasRole(['Usuario estándar'])){

            $usuario=Auth::user();
            $allCalificaciones = [];
        
            $tickets=Ticket::where('user_id', $usuario->id)->get();
     
            foreach($tickets as $ticket){
                 $calificaciones=Calificacion::where('ticket_id', $ticket->id)->get();
                 $allCalificaciones[] = $calificaciones;
            }
     
            return view('myViews.usuarioEst.calificaciones')->with('calificaciones', $allCalificaciones);
        }

    }

    // public function ver_calificacion($idComentario){

    //     $comentario=Comentario::find($idComentario);

    //     $idTicket=$comentario->ticket_id;
    //     $ticket= Ticket::find($idTicket);
    //     $respuesta=Respuesta::where('ticket_id', $idTicket)->first();
    
    //     return view('myViews.Admin.tickets.comentario', compact('idTicket', 'ticket', 'respuesta', 'comentario'));
    // }

    
    public function calificaciones_tk_jefeArea(){
       $usuario=Auth::user();
       $allCalificaciones = [];
       
       $tickets=Ticket::where('asignado_a', $usuario->name)->get();

       foreach($tickets as $ticket){
            $calificaciones=Calificacion::where('ticket_id', $ticket->id)->get();
            $allCalificaciones[] = $calificaciones;
       }

       return view('myViews.jefeArea.calificacionesTickets')->with('calificaciones', $allCalificaciones);

    }

 
 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
