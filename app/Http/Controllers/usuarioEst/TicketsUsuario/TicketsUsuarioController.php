<?php

namespace App\Http\Controllers\usuarioEst\TicketsUsuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CalificacionNotification;
use App\Events\CalificacionEvent;
use App\Events\TicketEvent;
use App\Events\MensajeClienteEvent;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Area;
use App\Models\Clasificacion;
use App\Models\Prioridad;
use App\Models\Estado;
use App\Models\TicketHistorial;
use App\Models\Mensaje;
use App\Models\Calificacion;


class TicketsUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets=Ticket::where('user_id', auth()->user()->id)->get();
        return view('myViews.usuarioEst.index')->with(['tickets'=> $tickets]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usuarios=auth()->user();
        $clasificacions=Clasificacion::all();
        $prioridads=Prioridad::all();
        $fecha_actual=Carbon::now()->format('d-m-Y');
        $estadoFirst= Estado::first();
        $tecnicos= User::role('Técnico de soporte')->get();

        return view('myViews.usuarioEst.create')->with('usuarios', $usuarios)
                                                ->with('clasificacions', $clasificacions)
                                                ->with('prioridads', $prioridads)
                                                ->with('fecha_actual', $fecha_actual)
                                                ->with('estadoFirst', $estadoFirst)
                                                ->with('tecnicos', $tecnicos);
    }                        

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
                'clasificacion_id' =>'required',
                'prioridad_id' =>'required',
                'asunto' =>'required',
                'mensaje' =>'required',
                'imagen' => 'image',
            ],
            [
                'clasificacion_id.required' => 'El campo clasificación es requerido',
                'prioridad_id.required' => 'El campo prioridad es requerido',
                'asunto.required' => 'El campo asunto es requerido',
                'mensaje.required' => 'El campo mensaje es requerido',
                'imagen.image' => 'El archivo debe ser una imagen',
            ]
        );

        $prioridad = Prioridad::find($request->prioridad_id); // Obtener la prioridad por ID
        $tiempoResolucion = $prioridad->tiempo_resolucion;

        if($request->hasFile('imagen')){

            $file = $request->file('imagen'); // obtenemos el archivo
            $random_name = time(); // le colocamos a la imagen un nombre random y con el tiempo y fecha actual 
            $destinationPath = 'images/tickets/'; // path de destino donde estaran las imagenes subidas 
            $extension = $file->getClientOriginalExtension();
            $filename = $random_name.'-'.$file->getClientOriginalName(); //concatemos el nombre random creado anteriormente con el nombre original de la imagen (para evitar nombres repetidos)
            $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename); //subimos y lo enviamos al path de Destin

            $ticket=new Ticket();
            $ticket->user_id=auth()->id();    
            $ticket->clasificacion_id=$request->clasificacion_id;
            $ticket->prioridad_id=$request->prioridad_id;
            $ticket->asunto=$request->asunto;
            $ticket->mensaje=$request->mensaje;
            $ticket->imagen=$filename;
            $ticket->fecha_inicio=Carbon::now();
            $ticket->estado_id=Estado::first()->id;
            $ticket->fecha_caducidad=Carbon::now()->addDays($tiempoResolucion);
            $ticket->save();

            // Enviamos el ticket al event, para despues crear la notificación 
            event(new TicketEvent($ticket));

        }else{

            $ticket=new Ticket();
            $ticket->user_id=auth()->id();    
            $ticket->clasificacion_id=$request->clasificacion_id;
            $ticket->prioridad_id=$request->prioridad_id;
            $ticket->asunto=$request->asunto;
            $ticket->mensaje=$request->mensaje;
            $ticket->fecha_inicio=Carbon::now();
            $ticket->estado_id=Estado::first()->id;
            $ticket->fecha_caducidad=Carbon::now()->addDays($tiempoResolucion);
            $ticket->save();

            // Enviamos el ticket al event, para despues crear la notificación 
            event(new TicketEvent($ticket));
        }

        $tickets=Ticket::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();
        return  redirect()->route('usuarios_tickets.index')->with(['tickets'=> $tickets, 'status'=> 'Ticket enviado exitosamente :)' ]);

    }

    // public function historial($idTicket){
        
    //     //Historial del ticket que viene por parametro
    //     $tickets = TicketHistorial::where('ticket_id', $idTicket)->get();
       
    //     $mensajes=MasInformacion::where('ticket_id', $idTicket)->get();

    //     return view('myViews.usuarioEst.historial' , compact ('idTicket', 'tickets', 'mensajes'));
    // }


    public function ver_ticketReportado($idTicket){
        $ticket= Ticket::find($idTicket);
        return view('myViews.usuarioEst.ticketReportado')->with(['ticket'=> $ticket, 'idTicket' => $idTicket]);
    }

    public function guardar_mensajeCliente(Request $request, $idTicket){

       $tick=Ticket::find($idTicket);
       $estadoTick=$tick->estado->nombre;


       if($estadoTick == "Resuelto"){

         try{

            $validator = $request->validate([
                    'opcion' =>'required',
                    'accion' => 'required'
                    
                ],
                [
                    'opcion.required' => 'El campo nivel de satisfacción es requerido.',
                    'accion.required' => 'Debes indicar que quieres hacer con el ticket.',
                ]
            
            );

              $accionSelect=$request->input('accion_seleccionada');

                $calificacion= new Calificacion();
                $calificacion->ticket_id = $idTicket;
                $calificacion->nivel_satisfaccion = $request->opcion;
                $calificacion->comentario=$request->comentario; 
                $calificacion->accion =  $request->input('accion_seleccionada');
                $calificacion->save();

               
                if ($accionSelect === 'Reabrirlo') {
                     $estadoReabierto =Estado::where('nombre', 'Reabierto')->first();
                     $tick->estado_id= $estadoReabierto->id;
                     $tick->save();
                } elseif ($accionSelect === 'Cerrarlo') {
                    $estadoCerrado =Estado::where('nombre', 'Cerrado')->first();
                    $tick->estado_id= $estadoCerrado->id;
                    $tick->save();
                }

        
                event(new CalificacionEvent($calificacion));


                return response()->json([
                    'nivel_satisfaccion' => $request->opcion,
                    'comentario' => $request->comentario,
                    'accion' => $request->input('accion_seleccionada'),
                    'status' => 'success',
                    'msjSuccess'  => 'Calificación enviada exitosamente.',
                
                ]);

           
            }catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'errors' => $e->validator->errors()->toArray(),
                ], 422);
            }

            
       }else{

            $esTecnico = false; 
            $usuario = Auth::user();
            $roles = $usuario->roles()->get();
            foreach ($roles as $role) {
                if ($role->name == "Administrador" || $role->name == "Jefe de área" || $role->name == "Técnico de soporte") {
                    $esTecnico = true;
                    break;
                }
            }
        
            try{
                        $validator = $request->validate([
                                'mensaje' =>'required',
                                'imagen' => 'image',
                            ],
                            [
                                'mensaje.required' => 'El campo mensaje es requerido',
                                'imagen.image' => 'El archivo debe ser una imagen',
                            ]
                        );


                    if($request->hasFile('imagen')){

                        $file = $request->file('imagen'); // obtenemos el archivo
                        $random_name = time(); // le colocamos a la imagen un nombre random y con el tiempo y fecha actual 
                        $destinationPath = 'images/msjCliente/'; // path de destino donde estaran las imagenes subidas 
                        $extension = $file->getClientOriginalExtension();
                        $filename = $random_name.'-'.$file->getClientOriginalName(); //concatemos el nombre random creado anteriormente con el nombre original de la imagen (para evitar nombres repetidos)
                        $uploadSuccess = $request->file('imagen')->move($destinationPath, $filename); //subimos y lo enviamos al path de Destin

                        $mensaje= new Mensaje();
                        $mensaje->user_id = Auth::user()->id;
                        $mensaje->ticket_id = $idTicket;
                        $mensaje->mensaje=$request->mensaje;
                        $mensaje->imagen=$filename;
                        $mensaje->save();

                        event(new MensajeClienteEvent($mensaje));

                        return response()->json([
                            'mensaje' => $request->mensaje,
                            'imagen' => $filename,
                            'esTecnico' => $esTecnico,
                            'status' => 'success',
                            'msjSuccess'  => 'Mensaje enviado exitosamente.',
                        
                        ]);

                    }else{  

                        $mensaje= new Mensaje();
                        $mensaje->user_id =  Auth::user()->id;
                        $mensaje->ticket_id = $idTicket;
                        $mensaje->mensaje=$request->mensaje;
                        $mensaje->save();

                        event(new MensajeClienteEvent($mensaje));

                        
                        return response()->json([
                            'mensaje' => $request->mensaje,
                            'esTecnico' => $esTecnico,
                            'status' => 'success',
                            'msjSuccess'  => 'Mensaje enviado exitosamente.',
                        ]);

                    }
                

                }catch (\Illuminate\Validation\ValidationException $e) {
                    return response()->json([
                        'errors' => $e->validator->errors()->toArray(),
                    ], 422);
                }
        }
             
        // return back()->with('status', 'Mensaje enviado exitosamente :)');
    }



    public function ticketEstado($ticketId){
        $ticket = Ticket::find($ticketId);
        
        return ['estado' => $ticket ? $ticket->estado->nombre : null];
    }

    




    // public function verRespuesta($idTicket, $idMensaje){
      
    //     $ticket=Ticket::find($idTicket);
       

    //     $ticketResueltos=TicketHistorial::where('ticket_id', $idTicket)->where('estado_id', 5)->get();

    //     // Obtener el ticket basado en la posición
    //     $registroMensaje = $ticketResueltos->skip($idMensaje - 1)->first();
                        
    //     $idMsj= $registroMensaje->mensaje_id;
    // dd($registroMensaje);  
    //     $respuestaTicket= Mensaje::find($idResp);
     
    //     return view('myViews.usuarioEst.respuesta')->with(['idTicket'=>$idTicket, 'ticket'=> $ticket,'respuesta' => $respuestaTicket]);
    // }


    // public function comentar_Respuesta(Request $request,$idRespuesta, $idTicket){

    //     $request->validate([
    //         'mensaje' =>'required',
    //         'opcion' => 'required',
    //     ],
    //     [
    //         'mensaje.required' => 'El campo mensaje es requerido',
    //         'opcion.required' => 'Debe seleccionar una opción',
    //     ]);

    //     $usuarioId=auth()->user()->id;
   
    //     $comentario=new Comentario();
    //     $comentario->respuesta_id=$idRespuesta;   
    //     $comentario->ticket_id=$idTicket;   
    //     $comentario->mensaje=$request->mensaje;
    //     $comentario->nivel_satisfaccion=$request->opcion;
    //     $comentario->bool_reabrir=$request->has('reabrir')? true : false;;
    //     $comentario->save();

     
       
    //     if($comentario->bool_reabrir){
    //         $ticket= Ticket::find($idTicket);
    //         $ticket->estado_id=6;
    //         $ticket->save();
    //     }

    //     $historial= new TicketHistorial();
    //     $historial->ticket_id= $idTicket;
    //     $historial->estado_id=6;
    //     $historial->updated_at= Carbon::now();
    //     $historial->save();

    //     //*NOTIFICACION A LOS USUARIOS AL COMENTAR
    //     // User::all()
    //     //     ->except($usuarioId)
    //     //     ->each(function(User $user) use ($comentario){
    //     //         $user->notify(new ComentarioNotification($comentario));
    //     //     });

    //     //* NOTIFICACION A LOS USUARIOS PERO UTILIZANDO EVENT Y LISTENER (más simplificado)
    //     // Enviamos el comentario al event,para luego crear la notificación
    //     event(new ComentarioEvent($comentario));

    //     return back()->with('status', 'Comentario enviado exitosamente :)');
       
    // }



   


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


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
