<?php

namespace App\Http\Controllers\Admin\Comentarios;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Respuesta;
use App\Models\Ticket;

class ComentarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 
    public function comentariosTodos(){

        $usuario= Auth::user();

        if ($usuario->hasRole(['Administrador'])) {
            $comentarios=Comentario::all();

            return view('myViews.Admin.tickets.comentariosTodos', compact('comentarios'));
            
        }elseif($usuario->hasRole(['Jefe de área'])){

            $usuario= Auth::user();
            $areasUsuario=$usuario->areas()->pluck('area_id');
          
            foreach ($areasUsuario as $area) {

                $ticketsArea=Ticket::where('clasificacion_id', $area)->get();
       
               
                foreach ($ticketsArea as $ticket){
                    // Obtiene todos los comentarios para el ticket actual y los agrega al array global
                    $ticketComentarios = Comentario::where('ticket_id', $ticket->id)->get();
                    $allComentarios[] = $ticketComentarios;
                }

            }                
            return view('myViews.jefeArea.comentariosArea')->with('comentarios', $allComentarios);

        }elseif($usuario->hasRole(['Técnico de soporte'])){

            $usuario=Auth::user();
        
            $tickets=Ticket::where('asignado_a', $usuario->name)->get();
     
            foreach($tickets as $ticket){
                 $comentarios=Comentario::where('ticket_id', $ticket->id)->get();
                 $allComentarios[] = $comentarios;
            }
     
            return view('myViews.tecnicoSop.misTickets.comentariosTickets')->with('comentarios', $allComentarios);
     
        }elseif($usuario->hasRole(['Usuario estándar'])){

            $usuario=Auth::user();
        
            $tickets=Ticket::where('user_id', $usuario->id)->get();
     
            foreach($tickets as $ticket){
                 $comentarios=Comentario::where('ticket_id', $ticket->id)->get();
                 $allComentarios[] = $comentarios;
            }
     
            return view('myViews.usuarioEst.comentarios')->with('comentarios', $allComentarios);
        }

    }

    public function ver_comentario($idComentario){

        $comentario=Comentario::find($idComentario);

        $idTicket=$comentario->ticket_id;
        $ticket= Ticket::find($idTicket);
        $respuesta=Respuesta::where('ticket_id', $idTicket)->first();
        $comentario= Comentario::find($idComentario);

        return view('myViews.Admin.tickets.comentario', compact('idTicket', 'ticket', 'respuesta', 'comentario'));
    }

    

    public function comentarios_tk_jefeArea(){
       $usuario=Auth::user();
       
       $tickets=Ticket::where('asignado_a', $usuario->name)->get();

       foreach($tickets as $ticket){
            $comentarios=Comentario::where('ticket_id', $ticket->id)->get();
            $allComentarios[] = $comentarios;
       }

       return view('myViews.jefeArea.comentariosTickets')->with('comentarios', $allComentarios);

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
