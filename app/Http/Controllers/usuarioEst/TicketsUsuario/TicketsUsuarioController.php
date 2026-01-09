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
use App\Services\GeminiService;
use App\Services\GroqService;
use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Area;
use App\Models\Servicio;
use App\Models\Prioridad;
use App\Models\Estado;
use App\Models\Mensaje;
use App\Models\Calificacion;


class TicketsUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $geminiService;

    public function __construct(GeminiService $geminiService){
 
         $this->geminiService = $geminiService;
    }


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
            $telegramService->sendMessage($tecnico->telegram_id, $message);
        }
    
        $tickets=Ticket::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();
        return  redirect()->route('usuarios_tickets.index')->with(['tickets'=> $tickets, 'status'=> 'Ticket enviado exitosamente :)' ]);
    }


    public function ver_ticketReportado($idTicket){
        $ticket= Ticket::find($idTicket);
        $tecnico=User::where('name', $ticket->asignado_a)->first();
        return view('myViews.usuarioEst.ticketReportado', compact('ticket', 'idTicket', 'tecnico'));
    }

  public function guardar_mensajeCliente(GeminiService $geminiService, Request $request, $idTicket) {

    $tick = Ticket::findOrFail($idTicket);
    $estadoTick = $tick->estado->nombre;
    $cliente = Auth::user();
    $tecnico = User::where('name', $tick->asignado_a)->first(); 
    $prioridad = Prioridad::find($tick->prioridad_id);
    $tiempoResolucion = $prioridad->tiempo_resolucion;

    // Bloque para cuando el TICKET ESTÁ RESUELTO (Calificación y Reabrir/Cerrar)
    if ($estadoTick == "Resuelto") {

        try {
            // Validar campos de calificación y acción
            $request->validate([
                'opcion' => 'required', // Nivel de satisfacción
                'accion_seleccionada' => 'required' // Opción de Reabrirlo o Cerrarlo
            ], [
                'opcion.required' => 'El campo nivel de satisfacción es requerido.',
                'accion_seleccionada.required' => 'Debes indicar si quieres Reabrirlo o Cerrarlo.',
            ]);

            $accionSelect = $request->input('accion_seleccionada');

            // Guardar la Calificación
            $calificacion = new Calificacion();
            $calificacion->ticket_id = $idTicket;
            $calificacion->nivel_satisfaccion = $request->opcion;
            $calificacion->comentario = $request->comentario;
            $calificacion->accion = $accionSelect;
            $calificacion->save(); // <-- Aquí SÍ se estaba guardando la calificación

            // Actualizar estado del ticket
            if ($accionSelect === 'Reabrirlo') {
                $estadoReabierto = Estado::where('nombre', 'Reabierto')->first();
                $tick->estado_id = $estadoReabierto->id;
                $tick->save();
            } elseif ($accionSelect === 'Cerrarlo') {
                $estadoCerrado = Estado::where('nombre', 'Cerrado')->first();
                $tick->estado_id = $estadoCerrado->id;
                $tick->save();
            }

            // Notificaciones
            CalificacionEvent::dispatch($calificacion);
            CalificacionCorreoEvent::dispatch($calificacion, $tecnico, $idTicket);
            
            // Notificación a Telegram
            $ticketLink = route('form_msjTecnico', ['idTicket' => $tick->id]);
            $message = "El cliente {$cliente->name} ha calificado la asistencia del ticket: {$calificacion->nivel_satisfaccion}: ({$ticketLink})";
            $telegramService = app(TelegramService::class);
            // Validar que el técnico tiene un telegram_id antes de enviar
            if ($tecnico && $tecnico->telegram_id) {
                 $telegramService->sendMessage($tecnico->telegram_id, $message);
            }

            return response()->json([
                'nivel_satisfaccion' => $request->opcion,
                'comentario' => $request->comentario,
                'accion' => $accionSelect,
                'status' => 'success',
                'msjSuccess' => 'Calificación enviada exitosamente.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->validator->errors()->toArray(),
            ], 422);
        }
        
    // Bloque para cuando el TICKET NO ESTÁ RESUELTO (Mensaje normal del cliente)
    } else {

        try {
            // --- Lógica de Validación Unificada (Similar a la que refactorizamos antes) ---
            $rules = [
                // Debe haber mensaje O imagen
                'mensaje' => 'required_without_all:imagen',
                'imagen' => 'image|nullable|required_without_all:mensaje', 
            ];

            $messages = [
                'mensaje.required_without_all' => 'Debes ingresar un mensaje o adjuntar una imagen.',
                'imagen.required_without_all' => 'Debes ingresar un mensaje o adjuntar una imagen.',
                'imagen.image' => 'El archivo debe ser una imagen.',
            ];

            $request->validate($rules, $messages);
            
            // --- Creación y Guardado del Mensaje ---
            
            $mensaje = new Mensaje();
            $mensaje->user_id = $cliente->id; // Usamos $cliente ya que ya lo obtuviste con Auth::user()
            $mensaje->ticket_id = $idTicket;
            $mensaje->mensaje = $request->mensaje;
            
            // Guardar imagen si hay
            if ($request->hasFile('imagen')) {
                $filename = $this->subirImagen($request->file('imagen'));
                $mensaje->imagen = $filename;
            }
            
            // GUARDAR MENSAJE
            $mensaje->save(); 
            
            // --- Lógica de Actualización del Ticket (Fecha de Caducidad) ---

            // Cambiar la fecha de caducidad agregando el tiempo de Resolucion
            // Se asume que $tick ya fue cargado al inicio de la función
            $tick->fecha_caducidad = Carbon::now()->addDays($tiempoResolucion);
            $tick->save();
            
            // --- Lógica de Notificaciones ---

            // Verificar si se guardó correctamente para enviar notificaciones
            if ($mensaje->wasRecentlyCreated) {
                // Notificación al sistema del técnico
                MensajeClienteEvent::dispatch($mensaje);
                // Notificación al correo del técnico
                MsjClienteCorreoEvent::dispatch($mensaje, $cliente, $tecnico, $idTicket);
        
                // Notificación al telegram del técnico
                $ticketLink = route('form_msjTecnico', ['idTicket' => $tick->id]);
                $message = "Nuevo mensaje del cliente {$cliente->name}: {$mensaje->mensaje}: ({$ticketLink})";
                $telegramService = app(TelegramService::class);
                
                // Validar que el técnico existe y tiene un telegram_id
                if ($tecnico && $tecnico->telegram_id) {
                    $telegramService->sendMessage($tecnico->telegram_id, $message);
                }
            }
            
            return response()->json([
                'mensaje' => $mensaje->mensaje, // Usar el valor del modelo para consistencia
                'imagen' => $mensaje->imagen ?? null,
                'status' => 'success',
                'msjSuccess' => 'Mensaje enviado exitosamente.',
                'msjId' => $mensaje->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'errors' => $e->validator->errors()->toArray(),
            ], 422);
        }
    }
}

    public function saveMsjUser(Request $request, $idTicket){

        $ticket= Ticket::find($idTicket);
        $tecnico=User::where('name', $ticket->asignado_a)->first();
        $cliente = Auth::user();

        $mensaje = new Mensaje();
        $mensaje->user_id = $cliente->id;
        $mensaje->ticket_id = $idTicket;

        // Guardad imagen si hay
        
        if ($request->hasFile('imagen')) {
            $filename = $this->subirImagen($request->file('imagen'));
            $mensaje->imagen = $filename;
        }
        
        $mensaje->mensaje = $request->input('mensaje');
        $mensaje->save();

        // Notificaciones
        if ($mensaje->wasRecentlyCreated || $mensaje->wasChanged()) {

            // Notificación al sistema del técnico
            MensajeClienteEvent::dispatch($mensaje);
            // Notificación al correo del técnico
            MsjClienteCorreoEvent::dispatch($mensaje, $cliente, $tecnico, $idTicket);
    
            // Notificación al telegram del técnico
            $ticketLink = route('form_msjTecnico', ['idTicket' => $ticket->id]);
            $message = "Nuevo mensaje del cliente {$cliente->name}: {$mensaje->mensaje}: ({$ticketLink})";
            $telegramService = app(TelegramService::class);
            $telegramService->sendMessage($tecnico->telegram_id, $message);
        }
    }

    private function subirImagen($file) {
        $random_name = time();
        $destinationPath = 'images/msjCliente/';
        $filename = $random_name . '-' . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        return $filename;
    }



    public function ticketEstado($ticketId){
        $ticket = Ticket::find($ticketId);
        
        return ['estado' => $ticket ? $ticket->estado->nombre : null];
    }


  
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
