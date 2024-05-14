<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Area;
use App\Models\Estado;
use App\Models\Respuesta;
use App\Models\MasInformacion;
use App\Models\RespMasInfo;
use App\Models\TicketHistorial;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets=Ticket::all();
        return view('myViews.Admin.tickets.index')->with('tickets', $tickets);
    }

    public function create()
    {
        return view('myViews.tickets.create');
    }


    public function area_tickets()
    {
        // Usuario autenticaco
        $usuario= Auth::user();

        if ($usuario->hasRole(['Administrador', 'Jefe de área', 'Técnico de soporte'])) {

            // Area que pertenece el usuario
            $areasUsuario=$usuario->areas()->pluck('area_id');

            // Tickets que pertenecen al área del asuario
            $tickets=Ticket::whereIn('clasificacion_id', $areasUsuario)->get();

            $cant_tkt_nuevos=$tickets->where('estado_id', 1 )->count();
            $cant_tkt_abiertos=$tickets->where('estado_id', 2 )->count();
            $cant_tkt_enEspera=$tickets->where('estado_id', 3 )->count();
            $cant_tkt_enRevision=$tickets->where('estado_id', 4)->count();
            $cant_tkt_resueltos=$tickets->where('estado_id', 5)->count();
            $cant_tkt_reAbiertos=$tickets->where('estado_id', 6)->count();
            $cant_tkt_cerrados=$tickets->where('estado_id', 7)->count();

            // Canti
            $estados = Estado::whereIn('nombre', ['Nuevo', 'Abierto', 'Reabierto'])->pluck('id');
            $fecha_actual=Carbon::now();
            $cant_tkt_vencidos = $tickets->whereIn('estado_id', $estados)->where('fecha_caducidad', '<', $fecha_actual)->count();
          
            // Pasar los tickets a la vista
            return view('myViews.Admin.tickets.ticketsArea' ,  compact('tickets', 'cant_tkt_nuevos', 
                                                                                 'cant_tkt_abiertos',
                                                                                 'cant_tkt_enEspera',
                                                                                 'cant_tkt_enRevision', 
                                                                                 'cant_tkt_resueltos',
                                                                                 'cant_tkt_reAbiertos',
                                                                                 'cant_tkt_cerrados',
                                                                                 'cant_tkt_vencidos'
                                                                )) ;

        }
    }


    public function tickets_noasignados()
    {   
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
       
  
        $estadoNuevo = Estado::where('nombre', 'Nuevo')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "nuevo"
        $ticketsNuevos = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estadoNuevo->id)->get();

        return view('myViews.Admin.tickets.noasignados')->with('tickets', $ticketsNuevos) ;
    }


    public function detalles_ticket($idTicket){

        $ticket=Ticket::find($idTicket);
        $usuario= Auth::user();

        return view('myViews.Admin.tickets.detalles')->with(['ticket'=> $ticket, 'usuario' =>$usuario]);
    }


    //* Método para que el técnico de soporte pueda asignarse un ticket
    public function asignar_ticket($idTicket){

        $ticket=Ticket::find($idTicket);

        // Asigna el usuario autenticado al ticket
        $ticket->asignado_a = Auth::user()->name;
         // cambiar estado a "abierto"
        $ticket->estado_id= 2;
        $ticket->save();

        $historial= new TicketHistorial();
        $historial->ticket_id= $idTicket;
        $historial->estado_id=2;
        $historial->updated_at= Carbon::now();
        $historial->save();


        return redirect()->back()->with('status', 'Asignación exitosa. EL TICKET HA SIDO ABIERTO');
        // return redirect()->route('form_Respuestaticket', ['idTicket' => $ticket->id])->with( 'status', 'Asignación exitosa. EL TICKET HA SIDO ABIERTO')->with('ticket', $ticket);

    }
    
    // TICKETS ASIGNADOS
    public function tickets_abiertos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
 
        $estadoAbierto = Estado::where('nombre', 'abierto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsAbiertos = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estadoAbierto->id)->get();


        return view('myViews.Admin.tickets.abiertos')->with('tickets', $ticketsAbiertos) ;
    }

    public function form_RespuestaRespondido($idTicket){
        $ticket=Ticket::find($idTicket);
        $ticket->estado_id = 5;
        $ticket->save();
        $respTkt=Respuesta::orderBy('fecha', 'desc')->where('ticket_id', $idTicket)->first();
        return view('myViews.Admin.tickets.form_TktRespondido')->with(['ticket'=> $ticket,'respuesta'=> $respTkt, 'idTicket' => $idTicket]);
    }

    public function form_Respuestaticket($idTicket){

        $ticket=Ticket::find($idTicket);
        session(['previous_url' => url()->previous()]);
  
        if (Respuesta::where('ticket_id', $idTicket)->exists()) {
            $ticket->estado_id = 5;
            $ticket->save();
            $respTkt=Respuesta::orderBy('fecha', 'desc')->where('ticket_id', $idTicket)->first();
            return view('myViews.Admin.tickets.form_TktRespondido')->with(['ticket'=> $ticket,'respuesta'=> $respTkt, 'idTicket' => $idTicket]);

        } else {
            return view('myViews.Admin.tickets.form_respuesta')->with(['ticket'=> $ticket, 'idTicket' => $idTicket]);
        }
    }
    
    public function guardar_respuestaTicket(Request $request, $idTicket){

        if($request->hasFile('imagen')){

            $file = $request->file('imagen'); // obtenemos el archivo
            $random_name = time(); // le colocamos a la imagen un nombre random y con el tiempo y fecha actual 
            $destinationPath = 'images/respuestas/tickets/'; // path de destino donde estaran las imagenes subidas 
            $extension = $file->getClientOriginalExtension();
            $filename = $random_name.'-'.$file->getClientOriginalName(); //concatemos el nombre random creado anteriormente con el nombre original de la imagen (para evitar nombres repetidos)
            $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename); //subimos y lo enviamos al path de Destin

            $respuesta= new Respuesta();
            $respuesta->ticket_id = $idTicket;
            $respuesta->mensaje=$request->mensaje;
            $respuesta->imagen=$filename;
            $respuesta->fecha=Carbon::now();
            $respuesta->save();

            
             // cambiar estado a "resuelto"
             $ticket=Ticket::find($idTicket);
             $ticket->estado_id= 5;
             $ticket->save();

             $historial= new TicketHistorial();
             $historial->ticket_id= $idTicket;
             $historial->estado_id=5;
             $historial->respuesta_id=$respuesta->id;
             $historial->updated_at= Carbon::now();
             $historial->save();

        }else{

            $respuesta= new Respuesta();
            $respuesta->ticket_id = $idTicket;
            $respuesta->mensaje=$request->mensaje;
            $respuesta->fecha=Carbon::now();
            $respuesta->save();

             // cambiar estado a "resuelto"
             $ticket=Ticket::find($idTicket);
             $ticket->estado_id= 5;
             $ticket->save();

             $historial= new TicketHistorial();
             $historial->ticket_id= $idTicket;
             $historial->estado_id=5;
             $historial->respuesta_id=$respuesta->id;
             $historial->updated_at= Carbon::now();
             $historial->save();

        }

        return back()->with('status', 'Ticket respondido exitosamente :)');
    }


    public function masInfo($idTicket){

        $ticket=Ticket::find($idTicket);
        $fecha_actual=Carbon::now()->format('d-m-Y');
        
      
        return view('myViews.Admin.tickets.masInfo')->with(['ticket'=> $ticket, 'fecha_actual'=> $fecha_actual]);
        
    }

    
    public function guardar_masInfo(Request $request, $idTicket)
    {
       
        $request->validate([
                'mensaje' =>'required',
                'imagen' => 'image',
            ],
            [
                'mensaje.required' => 'El campo mensaje es requerido',
                'imagen.image' => 'El archivo debe ser una imagen',
            ]
        );

        $usuario= Auth::user();
        $tickets = Ticket::where('asignado_a', $usuario->name )->get();

        
        if($request->hasFile('imagen')){

            $file = $request->file('imagen'); // obtenemos el archivo
            $random_name = time(); // le colocamos a la imagen un nombre random y con el tiempo y fecha actual 
            $destinationPath = 'images/masInfo/tickets/'; // path de destino donde estaran las imagenes subidas 
            $extension = $file->getClientOriginalExtension();
            $filename = $random_name.'-'.$file->getClientOriginalName(); //concatemos el nombre random creado anteriormente con el nombre original de la imagen (para evitar nombres repetidos)
            $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename); //subimos y lo enviamos al path de Destin

            $masInfo=new MasInformacion();
            $masInfo->ticket_id=$idTicket;   
            $masInfo->mensaje=$request->mensaje;
            $masInfo->imagen=$filename;
            $masInfo->fecha=Carbon::now();
            $masInfo->save();

            // cambiar estado a "en espera"
            $ticket=Ticket::find($idTicket);
            $ticket->estado_id= 3;
            $ticket->save();

            $historial= new TicketHistorial();
            $historial->ticket_id= $idTicket;
            $historial->estado_id=3;
            $historial->masinfo_id=$masInfo->id;
            $historial->updated_at= Carbon::now();
            $historial->save();

        
        }else{

            $masInfo=new MasInformacion();
            $masInfo->ticket_id=$idTicket;   
            $masInfo->mensaje=$request->mensaje;
            $masInfo->fecha=Carbon::now();
            $masInfo->save();

             // cambiar estado a "en espera"
            $ticket=Ticket::find($idTicket);
            $ticket->estado_id= 3;
            $ticket->save();

            $historial= new TicketHistorial();
            $historial->ticket_id= $idTicket;
            $historial->estado_id=3;
            $historial->masinfo_id=$masInfo->id;
            $historial->updated_at= Carbon::now();
            $historial->save();
        }

        return back()->with('status', 'Mensaje enviado exitosamente :)');
    
    }

    public function tickets_enEspera()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_enEspera = Estado::where('nombre', 'En espera')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_enEspera = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estado_enEspera->id)->get();

        return view('myViews.Admin.tickets.enEspera')->with('tickets', $tickets_enEspera) ;
             
    }

    public function tickets_enRevision()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_enRevision = Estado::where('nombre', 'En revisión')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_enRevision = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estado_enRevision->id)->get();

        return view('myViews.Admin.tickets.enRevision')->with('tickets', $tickets_enRevision) ;
             
    }


    public function tickets_vencidos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');

        $estados = Estado::whereIn('nombre', ['nuevo', 'abierto'])->pluck('id');
        
        $fecha_actual=Carbon::now();

        $ticketsVencidos = Ticket::whereIn('clasificacion_id', $areasUsuario)->whereIn('estado_id', $estados)->where('fecha_caducidad', '<', $fecha_actual)->get();

        return view('myViews.Admin.tickets.vencidos')->with('tickets', $ticketsVencidos) ;
    }
    
    public function tickets_resueltos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estadoResuelto = Estado::where('nombre', 'Resuelto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsResueltos = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estadoResuelto->id)->get();

        return view('myViews.Admin.tickets.resueltos')->with('tickets', $ticketsResueltos) ;
    }

    public function tickets_cerrados()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estadoCerrado = Estado::where('nombre', 'Cerrado')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsCerrado = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estadoCerrado->id)->get();

        return view('myViews.Admin.tickets.cerrados')->with('tickets', $ticketsCerrado) ;
    }

    public function tickets_reAbiertos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_reAbierto = Estado::where('nombre', 'Reabierto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_reAbierto = Ticket::whereIn('clasificacion_id', $areasUsuario)->where('estado_id', $estado_reAbierto->id)->get();

        return view('myViews.Admin.tickets.reAbiertos')->with('tickets', $tickets_reAbierto) ;
    }

    //Mostrar los usuarios de area con los tickets asignados
    public function tecnicos_tktAsignados(Request $request)
    {
         // usuario autenticado
        $usuario= Auth::user();

        // Id de las areas del usuario autenticado
        $areasUsuarioAuth=$usuario->areas()->pluck('area_id');

        // Usuarios que tiene el rol "soporte tecnico" y que pertenecen a las areas del usuario auth
        $usuarios = User::role('Técnico de soporte')
            ->whereHas('areas', function ($query) use ($areasUsuarioAuth) {
                $query->whereIn('area_id', $areasUsuarioAuth);
            })
            ->get();

        // Cantidad de veces que los usuarios tecnicos estan asignados a un ticket
        foreach ($usuarios as $usuario) {

            $usuario->ticket_count_a = Ticket::where('asignado_a', $usuario->name)->where('estado_id', 2 )->count();
            $usuario->ticket_count_esp = Ticket::where('asignado_a', $usuario->name)->where('estado_id', 3 )->count();
        }

        return view('myViews.Admin.tickets.asignarTecnico')->with(['usuarios'=> $usuarios]);
    }


    public function asignar_ticket_a_tecnico(Request $request, $usuarioId){

        // Obtener el usuario por el id pasado por parametro
        $usuario=User::find($usuarioId); 

        // Cantidad de tickets asignados que tiene el usuario seleccionado
        $Usuarioticket_Count = Ticket::where('asignado_a', $usuario->name)->count(); 
    
        // ASIGNAR TICKET A USUARIO SELECCIONADO
 
        $previousUrl = url()->previous();   // Url anteriormente visitada
        $ticketId = basename($previousUrl); // Obtenemos el id del ticket de la url anteriormente visitada
       
        $ticket=Ticket::find($ticketId);  // Obtenemos el ticket por el Id anteriormente obtenido

        $ticket->asignado_a =$usuario->name ;  // asignamos el nombre del usuario tecnico seleccionado
        $ticket->estado_id= 2;  // cambiar estado a "abierto"
        $ticket->save();  // Guardamos

        return redirect()->back()->with('status', 'Ticket asignado exitosamente');
           
    }

    public function tkt_abierto_tecnico($usuarioId){

        $usuario= User::find($usuarioId);
   
        if ($usuario->hasRole(['Técnico de soporte', 'Jefe de área'])) {

            $estadoAbierto = Estado::where('nombre', 'Abierto')->first();

            $usuario_ticketsAbiertos = Ticket::where('asignado_a', $usuario->name)->where('estado_id', $estadoAbierto->id)->get();

            return view('myViews.Admin.tickets.tkts_abiertos_tecnico')->with(['usuario'=> $usuario, 'tickets'=> $usuario_ticketsAbiertos]);


        }
    }

    public function tkt_enEspera_tecnico($usuarioId){

        $usuario= User::find($usuarioId);
   
        if ($usuario->hasRole(['Técnico de soporte', 'Jefe de área'])) {

            $estadoEspera = Estado::where('nombre', 'En espera')->first();

            // tickets en espera asignados al agente
            $tickets = Ticket::where('asignado_a', $usuario->name)->where('estado_id', $estadoEspera->id)->get();

            return view('myViews.Admin.tickets.tkts_enEspera_tecnico', compact('usuario', 'tickets'));
 
        } 
    }

    // Ver tickets desde la vista de los ticket asignados a los tecnicos(abiertos, en espera) 
    public function verTicket($idTicket){
        
       $ticket=Ticket::find($idTicket);
        return view('myViews.Admin.tickets.verTicket')->with(['ticket'=> $ticket]);
    }

    
    public function verRespCliente_masInfo( $idMensaje, $idTicket){

         //Obtener el historial que esta en la posicion que viene en el parametro $idMensaje (ya que pueden haber registros eliminados)
         $hist_Posicion=TicketHistorial::where('ticket_id', $idTicket)->where('estado_id', 3)->whereNotNull('masinfo_id')
                                                                                             ->orderBy('id', 'desc')->first();
         $idMasInfo=$hist_Posicion->masinfo_id; 
      
         //ultimo Mensaje del Agente  
         $mensaje=MasInformacion::where('id', $idMasInfo)->latest('created_at')->first();

        // respuesta usuario
         $respuesta=RespMasInfo::where('masInfo_id', $mensaje->id)->first();

        if (Respuesta::where('ticket_id', $idTicket)->exists()) {

            $solucion=Respuesta::where('ticket_id', $idTicket)->first();

            return view('myViews.Admin.tickets.ticketRespondido', compact('idTicket', 'mensaje', 'idMasInfo', 'respuesta', 'solucion'));
             
        }else{
            return view('myViews.Admin.tickets.verRespCliente_masInfo', compact('idTicket', 'mensaje', 'idMasInfo', 'respuesta'));
        }
      

         
       
    }

    public function historialTicket($ticket_id){

        // Incidente
        $ticket= ticket::find($ticket_id);
    
        // Mas info 
        $masInfo = MasInformacion::where('ticket_id',$ticket_id)->get(); 
     
        // respuesta de masInfo (cliente)
        $respMasInfo = RespMasInfo::where('ticket_id',$ticket_id)->get();
       
        // solucion del ticket
        $solucion=Respuesta::where('ticket_id',$ticket_id)->first();

        return view('myViews.Admin.tickets.historialTicket', compact('ticket_id', 'ticket', 'masInfo','respMasInfo','solucion' ));
       
    }

    public function volverDetalles($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if ($ticket->technician_id) {
            return response()->json(['assigned' => true]);
        } else {
            return response()->json(['assigned' => false]);
        }
    }










    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket= Ticket::find($id);
        $ticket->delete();

        return redirect()->route('tickets.index')->with('eliminar' , 'ok');
  
    }
}
