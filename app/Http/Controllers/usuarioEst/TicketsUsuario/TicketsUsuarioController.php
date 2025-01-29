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
use App\Events\TicketCorreoEvent;
use App\Events\MensajeClienteEvent;
use App\Events\MsjClienteCorreoEvent;
use App\Events\CalificacionCorreoEvent;
use App\Services\TelegramService;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Area;
use App\Models\Servicio;
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
        $areas=Area::all();
        $servicios=Servicio::all();
        $prioridades=Prioridad::all();
        $fecha_actual=Carbon::now()->format('d-m-Y');
        $estadoFirst= Estado::first();
        $tecnicos= User::role('Técnico de soporte')->get();

        return view('myViews.usuarioEst.create', compact('usuarios','areas', 'servicios', 'prioridades', 'fecha_actual', 'estadoFirst', 'tecnicos'));
                                           
    }        
    
    public function servicios_area($areaId){

        $area= Area::find($areaId);
        
        // servicios de area
        $serviciosArea=$area->servicios()->get();
       
        return response()->json($serviciosArea);

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
            'area_id' => 'required',
            'servicio_id' => 'required',
            'prioridad_id' => 'required',
            'asunto' => 'required',
            'mensaje' => 'required',
            'imagen' => 'image|nullable', 
        ], [
            'area_id.required' => 'El campo área es requerido',
            'servicio_id.required' => 'El campo servicio es requerido',
            'prioridad_id.required' => 'El campo prioridad es requerido',
            'asunto.required' => 'El campo asunto es requerido',
            'mensaje.required' => 'El campo mensaje es requerido',
            'imagen.image' => 'El archivo debe ser una imagen',
        ]);
    
        $prioridad = Prioridad::find($request->prioridad_id);
        $tiempoResolucion = $prioridad->tiempo_resolucion;
    
        $area = Area::find($request->area_id);
        $notif_telegram = $area->notif_telegram;
    
        // Obtener técnicos según la notificación de Telegram
        $tecnicos = User::whereHas('areas', function ($query) {
            $query->where('area_id', request('area_id'));
        })->role($notif_telegram == 'Todos' ? ['Administrador', 'Técnico de soporte', 'Jefe de área'] : ['Administrador', 'Jefe de área'])->get();
    
        // Manejo de la imagen
        $filename = null;
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move('images/tickets/', $filename);
        }
    
        // Crear el ticket
        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'area_id' => $request->area_id,
            'servicio_id' => $request->servicio_id,
            'prioridad_id' => $request->prioridad_id,
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
            'imagen' => $filename,
            'fecha_inicio' => Carbon::now(),
            'estado_id' => Estado::first()->id,
            'fecha_caducidad' => Carbon::now()->addDays($tiempoResolucion),
        ]);
    
        // Enviar eventos
        event(new TicketEvent($ticket));
        event(new TicketCorreoEvent($ticket));
    
        // Notificación a Telegram
        foreach ($tecnicos as $tecnico) {
            $ticketLink = route('detalles_ticket', ['idTicket' => $ticket->id]);
            $message = "Nuevo ticket creado: {$ticket->asunto}: ({$ticketLink})";
            $telegramService = app(TelegramService::class);
            $telegramService->sendMessage($tecnico->telegram, $message);
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
        $tecnico=User::where('name', $ticket->asignado_a)->first();
        return view('myViews.usuarioEst.ticketReportado', compact('ticket', 'idTicket', 'tecnico'));
    }

    public function guardar_mensajeCliente(Request $request, $idTicket){

       $tick=Ticket::find($idTicket);
       $estadoTick=$tick->estado->nombre;
       $cliente=Auth::user();
       $tecnico=User::where('name', $tick->asignado_a)->first();
       $prioridad = Prioridad::find($tick->prioridad_id); // Obtener la prioridad por ID
       $tiempoResolucion = $prioridad->tiempo_resolucion;

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

                // Notificación en el sistema del técnico
                CalificacionEvent::dispatch($calificacion);
                // Notificación al correo del técnico
                CalificacionCorreoEvent::dispatch($calificacion, $tecnico, $idTicket);

                // Notificación al telegram del técnico
                $ticketLink = route('form_msjTecnico', ['idTicket' => $tick->id]); 
                $message = "El cliente {$cliente->name} ha calificado la asistencia del ticket: {$calificacion->nivel_satisfaccion}: ({$ticketLink})";
                $telegramService = app(TelegramService::class);
                $response = $telegramService->sendMessage($tecnico->telegram, $message);


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

            try{
                $validator = $request->validate([
                    'mensaje' => 'required',
                    'imagen' => 'image|nullable',
                ], [
                    'mensaje.required' => 'El campo mensaje es requerido',
                    'imagen.image' => 'El archivo debe ser una imagen',
                ]);
                
                $mensaje = new Mensaje();
                $mensaje->user_id = Auth::user()->id;
                $mensaje->ticket_id = $idTicket;
                $mensaje->mensaje = $request->mensaje;
                
                //Guardar imagen si hay
                if ($request->hasFile('imagen')) {
                    $filename = time() . '-' . $request->file('imagen')->getClientOriginalName();
                    $request->file('imagen')->move('images/msjCliente', $filename);
                    $mensaje->imagen = $filename;
                }

                //Fecha del msj
                // $mensaje->created_at =  Carbon::now()->addHours(5);
                // $mensaje->updated_at =  Carbon::now()->addHours(5);

                //Guardar
                $mensaje->save();
                
                // Cambiar la fecha de caducidad agregando el tiempo de Resolucion
                $ticket = Ticket::find($idTicket);
                $ticket->fecha_caducidad = Carbon::now()->addDays($tiempoResolucion);
                $ticket->save();
                
                // Notificacion al sistema del técnico
                MensajeClienteEvent::dispatch($mensaje);
                 // Notificacion al correo del técnico
                MsjClienteCorreoEvent::dispatch($mensaje, $cliente, $tecnico, $idTicket);
                
                // Notificacion al telegram del técnico
                $ticketLink = route('form_msjTecnico', ['idTicket' => $ticket->id]);
                $message = "Nuevo mensaje del cliente {$cliente->name}: {$mensaje->mensaje}: ({$ticketLink})";
                $telegramService = app(TelegramService::class);
                $telegramService->sendMessage($tecnico->telegram, $message);
                
                return response()->json([
                    'mensaje' => $request->mensaje,
                    'imagen' => $mensaje->imagen ?? null,
                    'status' => 'success',
                    'msjSuccess' => 'Mensaje enviado exitosamente.',
                ]);
                

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
