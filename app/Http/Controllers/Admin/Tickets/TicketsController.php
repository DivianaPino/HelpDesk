<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Events\MensajeTecnicoEvent;
use App\Events\TicketAsignadoCorreoEvent;
use App\Events\TicketReasignadoCorreoEvent;
use App\Events\MsjTecnicoCorreoEvent;
use App\Events\TicketResueltoCorreoEvent;
use App\Services\TelegramService;
use App\Services\GeminiService;
use App\Services\GroqService;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\Area;
use App\Models\Servicio;
use App\Models\Estado;
use App\Models\Mensaje;
use App\Models\Calificacion;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Transporters\HttpTransporter;
use GuzzleHttp\Client as GuzzleClient;
use Gemini\Client;
use Illuminate\Validation\Rule;

use App\Rules\sentimientoTextoRule;


class TicketsController extends Controller
{

    protected $geminiService;
    protected $groqService;


    public function __construct(GeminiService $geminiService, GroqService $groqService){

        $this->geminiService = $geminiService;
        $this->groqService = $groqService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = Auth::user();
        $tickets = Ticket::query();
        $areas = Area::all();
    
        // Obtener parámetros de filtro desde la solicitud
        $selectedAreaId = request()->input('area', null);
        $selectedServicioId = request()->input('servicio', null);
    
        // Aplicar filtros por area si están definidos
        if ($selectedAreaId !== null && $selectedAreaId !== '') {
            $tickets = $tickets->where('area_id', $selectedAreaId);
        }

        // servicios que pertenecen al área seleccionada
        $serviciosArea= Servicio::where('area_id', $selectedAreaId)->get();
     
    
           // Aplicar filtros por servicio si están definidos
        if ($selectedServicioId !== null && $selectedServicioId !== '') {
            $tickets = $tickets->where('servicio_id', $selectedServicioId);
        }
    
        // Mostrar todos los tickets si ninguna opción está seleccionada
        if ($selectedAreaId === '' && $selectedServicioId === '') {
            $tickets = $tickets->get();
        } else {
            $tickets =  $tickets->get();
        }

      
        return view('myViews.Admin.tickets.index', compact('usuario', 'tickets', 'areas', 'serviciosArea', 
                                                            'selectedAreaId',  'selectedServicioId'));
    }

public function filtrarTickets(Request $request)
{
    $area_id = $request->input('area');

    $tickets = Ticket::when($area_id, function ($query) use ($area_id) {
        return $query->whereHas('area', function ($q) use ($area_id) {
            return $q->where('id', $area_id);
        });
    });

    return DataTables::eloquent($tickets)
        ->make(true);
}

    
    


    public function create()
    {
        return view('myViews.tickets.create');
    }


    public function area_tickets(Request $request)
    {
        // Usuario autenticaco
        $usuario= Auth::user();

        // Obtener las áreas del usuario
        $areasUsuario = $usuario->areas()->pluck('area_id');

        // Obtener parámetros de filtro desde la solicitud
        $selectedAreaId = $request->input('area', null);
        $selectedServicioId = $request->input('servicio', null);

        // obtener los tickets que pertenecen a las areas del tecnico
        $query = Ticket::whereIn('area_id', $areasUsuario);

        // Aplicar filtro por área si está definido
        if (!is_null($selectedAreaId) && $selectedAreaId !== '') {
            $query->where('area_id', $selectedAreaId);
        }

        // Aplicar filtro por servicio si está definido
        if (!is_null($selectedServicioId) && $selectedServicioId !== '') {
            $query->where('servicio_id', $selectedServicioId);
        }

        // Obtener los tickets filtrados
        $tickets = $query->get();

        // Obtener las áreas y servicios para los select
        $areas = $usuario->areas()->get();
        $serviciosArea = Servicio::where('area_id', $selectedAreaId)->get();

        //Leyenda tickets del tecnico 
        $cant_tkt_nuevos=$tickets->where('estado_id', 1 )->count();
        $cant_tkt_abiertos=$tickets->where('estado_id', 2 )->count();
        $cant_tkt_enEspera=$tickets->where('estado_id', 3 )->count();
        $cant_tkt_resueltos=$tickets->where('estado_id', 4)->count();
        $cant_tkt_reAbiertos=$tickets->where('estado_id', 5)->count();
        $cant_tkt_cerrados=$tickets->where('estado_id', 6)->count();

        // cantidad de tickets vencidos
        $estados = Estado::whereIn('nombre', ['Nuevo', 'Abierto', 'Reabierto'])->pluck('id');
        $fecha_actual=Carbon::now();
        $cant_tkt_vencidos = $tickets->whereIn('estado_id', $estados)->where('fecha_caducidad', '<', $fecha_actual)->count();
        
        // Pasar los tickets a la vista
        return view('myViews.Admin.tickets.ticketsArea' ,  compact('tickets', 'areas', 'serviciosArea', 
                                                                   'selectedAreaId',  'selectedServicioId',
                                                                    'cant_tkt_nuevos', 'cant_tkt_abiertos',
                                                                    'cant_tkt_enEspera','cant_tkt_resueltos',
                                                                    'cant_tkt_reAbiertos','cant_tkt_cerrados',
                                                                    'cant_tkt_vencidos','usuario'
                                                            )) ;
    }


    public function tickets_noasignados()
    {   
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
       
  
        $estadoNuevo = Estado::where('nombre', 'Nuevo')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "nuevo"
        $ticketsNuevos = Ticket::whereIn('area_id', $areasUsuario)->where('estado_id', $estadoNuevo->id)->get();

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

        if (!is_null($ticket->asignado_a)) {
            return redirect()->back()->with('error', 'EL TICKET YA HA SIDO ATENDIDO POR OTRO AGENTE');
        }else{ 

            // Asigna el usuario autenticado al ticket
            $ticket->asignado_a = Auth::user()->name;
            // cambiar estado a "abierto"
            $ticket->estado_id= 2;
            $ticket->save();

            return redirect()->back()->with('status', 'Asignación exitosa. EL TICKET HA SIDO ABIERTO');
        // return redirect()->route('form_Respuestaticket', ['idTicket' => $ticket->id])->with( 'status', 'Asignación exitosa. EL TICKET HA SIDO ABIERTO')->with('ticket', $ticket);
        }
    }
    
    // TICKETS ASIGNADOS
    public function tickets_abiertos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
 
        $estadoAbierto = Estado::where('nombre', 'abierto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsAbiertos = Ticket::whereIn('area_id', $areasUsuario)->where('estado_id', $estadoAbierto->id)->get();

        return view('myViews.Admin.tickets.abiertos')->with('tickets', $ticketsAbiertos) ;
    }

    // public function form_RespuestaRespondido($idTicket){
    //     $ticket=Ticket::find($idTicket);
    //     $ticket->estado_id = 5;
    //     $ticket->save();
    //     $respTkt=Mensaje::orderBy('fecha', 'desc')->where('ticket_id', $idTicket)->first();
    //     return view('myViews.Admin.tickets.form_TktRespondido')->with(['ticket'=> $ticket,'respuesta'=> $respTkt, 'idTicket' => $idTicket]);
    // }

    public function form_msjTecnico($idTicket){

        $ticket=Ticket::find($idTicket);
        $cliente=User::find($ticket->user_id);
        session(['previous_url' => url()->previous()]);

        return view('myViews.Admin.tickets.form_msjTecnico', compact('ticket', 'idTicket','cliente'));
  
        // if (Mensaje::where('ticket_id', $idTicket)->exists()) {
        //     return view('myViews.Admin.tickets.form_respuesta')->with(['ticket'=> $ticket, 'idTicket' => $idTicket]);
        // }
        // elseif (Mensaje::where('ticket_id', $idTicket)->exists()) {
        //     // $ticket->estado_id = 5;
        //     // $ticket->save();
        //     $msjTkt=Mensaje::orderBy('fecha', 'desc')->where('ticket_id', $idTicket)->first();
        //     return view('myViews.Admin.tickets.form_TktRespondido')->with(['ticket'=> $ticket,'mensaje'=> $msjTkt, 'idTicket' => $idTicket]);

        // }else {
        //     return view('myViews.Admin.tickets.form_respuesta')->with(['ticket'=> $ticket, 'idTicket' => $idTicket]);
        // }
    }
    
    public function guardar_mensajeTecnico(GeminiService $geminiService, Request $request, $idTicket){

       
        $ticket = Ticket::findOrFail($idTicket);
        $cliente = User::find($ticket->user_id);
        $tecnico = Auth::user();


        try{
       
            if ($request->input('resuelto') === 'on') {
                $validator = $request->validate(
                    [
                        'mensaje' => 'nullable',
                        'imagen' => 'image|nullable',
                    ],
                    [
                        'imagen.image' => 'El archivo debe ser una imagen',
                    ]
                );
            } else {
                if (empty($request->input('mensaje')) && $request->hasFile('imagen')) {
                    $validator = $request->validate(
                        [
                            'mensaje' => 'nullable',
                            'imagen' => 'image',
                        ],
                        [
                            'imagen.image' => 'El archivo debe ser una imagen',
                        ]
                    );
                } else {
                    $validator = $request->validate(
                        [
                            'mensaje' => 'required',
                            'imagen' => 'image|nullable',
                        ],
                        [
                            'mensaje.required' => 'El campo mensaje es requerido',
                            'imagen.image' => 'El archivo debe ser una imagen',
                        ]
                    );
                }
            }
                
                
                $mensaje = new Mensaje();
                $mensaje->user_id = $tecnico->id;
                $mensaje->ticket_id = $idTicket;

                // Guardad imagen si hay
                if ($request->hasFile('imagen')) {
                    $filename = $this->subirImagen($request->file('imagen'));
                    $mensaje->imagen = $filename;
                }

                $textAnalizado ='';
                $textErrores='';

                try {
                    // ANALISIS DEL MENSAJE CON GEMINI

                    // Analizar el sentimiento o estado de ánimo que transmite el mensaje
                    $geminiService = new GeminiService();
                    $resultSentimiento=$geminiService->generateSentiment($request->mensaje);
                    $textAnalizado = $resultSentimiento['candidates'][0]['content']['parts'][0]['text'];

                    //Texto reescrito con un estado de ánimo positivo
                    $resultRewrite=$geminiService->rewriteText($request->mensaje);
                    $textRewrite =  $resultRewrite['candidates'][0]['content']['parts'][0]['text'];

                    // Verificar si el texto tiene errores ortográficos o gramaticales
                    $resultErrores=$geminiService->SpellingError($request->mensaje);
                    $textErrores = $resultErrores['candidates'][0]['content']['parts'][0]['text'];

                    //Texto corregido
                    $resultCorreccion=$geminiService->CorrectErrors($request->mensaje);
                    $textCorregido = $resultCorreccion['candidates'][0]['content']['parts'][0]['text'];

                    // si el estado de ánimo no es positivo y tiene errores ortograficos o gramaticales
                    // mostrar mensaje de error y no guardarlo
                    if($textAnalizado === "Negativo.\n" && $textErrores === "No\n"){
                    
                        return response()->json([
                            'animoNegativo' => 'El estado de ánimo del mensaje debe ser positivo',
                            'textoReescrito' => $textRewrite,
                        ]);
                        
                    }elseif($textAnalizado === "Neutral.\n" && $textErrores === "No\n"){
                        return response()->json([
                            'animoNegativo' => 'El estado de ánimo del mensaje debe ser positivo',
                            'textoReescrito' => $textRewrite,
                        ]);

                    }elseif($textAnalizado === "Positivo.\n" && $textErrores === "No\n"){
                        $mensaje->mensaje = $request->mensaje;
                        $mensaje->save();

                    }elseif($textErrores === "Sí\n"){
                        return response()->json([
                            'textoErrores' => 'El texto tiene errores ortográficos o gramaticales',
                            'textoCorregido' => $textCorregido,
                        ]);
                    }elseif(empty($request->input('mensaje')) && $request->hasFile('imagen')){
                        $mensaje->save();
                    }

                } catch (\Exception $e) {

                    try{

                        // ANALISIS DEL MENSAJE CON GROQ

                        // Analizar el sentimiento o estado de ánimo que transmite el mensaje
                        $groqService = new GroqService();
                        $resultSentimiento_groq=$groqService->generateSentiment($request->mensaje);
                        $textAnalizado_groq = $resultSentimiento_groq['choices'][0]['message']['content'];
                        $textAnalizado_groq = rtrim($textAnalizado_groq, '.');
                    
                        //Texto reescrito con un estado de ánimo positivo
                        $resultRewrite_groq=$groqService->rewriteText($request->mensaje);
                        $textRewrite_groq =  $resultRewrite_groq['choices'][0]['message']['content']; 

                        // Verificar si el texto tiene errores ortográficos o gramaticales
                        $resultErrores_groq=$groqService->SpellingError($request->mensaje);
                        $textErrores_groq = $resultErrores_groq['choices'][0]['message']['content'];
                        $textErrores_groq = rtrim($textErrores_groq, '.');
                    
                        //Texto corregido
                        $resultCorreccion_groq=$groqService->CorrectErrors($request->mensaje);
                        $textCorregido_groq = $resultCorreccion_groq['choices'][0]['message']['content'];

                        // si el estado de ánimo no es positivo y tiene errores ortograficos o gramaticales
                        // mostrar mensaje de error y no guardarlo
                        if($textAnalizado_groq === "Negativo" && $textErrores_groq === "No"){
                        
                            return response()->json([
                                'animoNegativo' => 'El estado de ánimo del mensaje debe ser positivo',
                                'textoReescrito' => $textRewrite_groq,
                            ]);
                            
                        }elseif($textAnalizado_groq === "Neutral" && $textErrores_groq === "No"){
                            return response()->json([
                                'animoNegativo' => 'El estado de ánimo del mensaje debe ser positivo',
                                'textoReescrito' => $textRewrite_groq,
                            ]);

                        }elseif($textAnalizado_groq === "Positivo" && $textErrores_groq === "No"){
                            $mensaje->mensaje = $request->mensaje;
                            $mensaje->save();

                        }elseif($textErrores_groq === "Sí"){
                            return response()->json([
                                'textoErrores' => 'El texto tiene errores ortográficos o gramaticales',
                                'textoCorregido' => $textCorregido_groq,
                            ]);
                        }elseif(empty($request->input('mensaje')) && $request->hasFile('imagen')){
                            $mensaje->save();
                        }

                    }catch (\Exception $e) {

                        //Si las las dos IA falla que se guarde el mensaje sin analizar
                        $mensaje->mensaje = $request->mensaje;
                        $mensaje->save();

                    }

                }

                $estadoTicket = Estado::find($ticket->estado_id);

                if($estadoTicket->nombre != "En espera" && $ticket->mensajes){
                    foreach($ticket->mensajes as $msj){
                        if($msj->user->hasAnyRole(['Administrador', 'Jefe de área', 'Técnico de soporte'])){
                            $ticket->estado_id = Estado::where('nombre', 'En espera')->first()->id;
                            $ticket->save();
                            break;
                        }  
                    }
                }

                $esResuelto = 'No';

                if($request->input('resuelto') === 'on') {
                    $esResuelto = 'Si';
                    $ticket->estado_id = Estado::where('nombre', 'Resuelto')->first()->id;
                    $ticket->save();
                }

                $this->notificacionCliente($mensaje, $cliente, $tecnico, $idTicket,$esResuelto);

                
                return response()->json([
                    'mensaje' => $request->mensaje,
                    'imagen' => $mensaje->imagen ?? null,
                    'status' => 'success',
                    'msjSuccess'  => 'Mensaje enviado exitosamente.',
                    'msjId' => $mensaje->id
                ]);
            
                
        
            }catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'errors' => $e->validator->errors()->toArray(),
                ], 422);
            }
        
       
    }

    private function subirImagen($file) {
        $random_name = time();
        $destinationPath = 'images/msjTecnico/';
        $filename = $random_name . '-' . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        return $filename;
    }

    private function notificacionCliente($mensaje, $cliente, $tecnico, $idTicket, $esResuelto) {

        MensajeTecnicoEvent::dispatch($mensaje);

        if($esResuelto == 'Si'){
            TicketResueltoCorreoEvent::dispatch($mensaje, $cliente, $tecnico, $idTicket);
        }else{
            MsjTecnicoCorreoEvent::dispatch($mensaje, $cliente,$tecnico, $idTicket);
        }

        $ticketLink = route('ver_ticketReportado', ['idTicket' => $idTicket]);
        $mensajeTexto = is_null($mensaje) ? "El ticket ha sido resuelto" : $mensaje->mensaje;
        $message = "Nuevo mensaje del técnico {$tecnico->name}: {$mensajeTexto}: ({$ticketLink})";
        $telegramService = app(TelegramService::class);
        $telegramService->sendMessage($cliente->telegram_id, $message);
    }
    
    // private function notificacionClienteResuelto($mensaje, $cliente, $tecnico, $idTicket) {

    //     //Notificacion en el sistema del cliente
    //     $mensajeSistema =$mensaje->mensaje ?? "El ticket ha sido resuelto";;
    //     MensajeTecnicoEvent::dispatch($mensajeSistema);

    //     //Notificacion al correo del cliente
    //     TicketResueltoCorreoEvent::dispatch($mensaje, $cliente, $tecnico, $idTicket);
    
    //     //Notificacion al telegram del cliente
    //     $ticketLink = route('ver_ticketReportado', ['idTicket' => $idTicket]);
    //     $mensajeTexto = is_null($mensaje) ? "El ticket ha sido resuelto" : $mensaje->mensaje;
    //     $message = "Nuevo mensaje del técnico {$tecnico->name}: {$mensajeTexto}: ({$ticketLink})";
    //     $telegramService = app(TelegramService::class);
    //     $telegramService->sendMessage($cliente->telegram_id, $message);
    // }
    
    public function navbar_notifications(){
        return view('vendor.adminlte.components.layout.navbar-notification');
    }

    public function tickets_enEspera()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_enEspera = Estado::where('nombre', 'En espera')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_enEspera = Ticket::whereIn('area_id', $areasUsuario)->where('estado_id', $estado_enEspera->id)->get();

        return view('myViews.Admin.tickets.enEspera')->with('tickets', $tickets_enEspera) ;
             
    }

    public function tickets_enRevision()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_enRevision = Estado::where('nombre', 'En revisión')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_enRevision = Ticket::whereIn('area_id', $areasUsuario)->where('estado_id', $estado_enRevision->id)->get();

        return view('myViews.Admin.tickets.enRevision')->with('tickets', $tickets_enRevision) ;
             
    }


    public function tickets_vencidos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');

        $estados = Estado::whereIn('nombre', ['Nuevo', 'Abierto', 'Reabierto'])->pluck('id');
        
        $fecha_actual=Carbon::now();

        $ticketsVencidos = Ticket::whereIn('area_id', $areasUsuario)->whereIn('estado_id', $estados)->where('fecha_caducidad', '<', $fecha_actual)->get();

        return view('myViews.Admin.tickets.vencidos')->with('tickets', $ticketsVencidos) ;
    }
    
    public function tickets_resueltos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estadoResuelto = Estado::where('nombre', 'Resuelto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $ticketsResueltos = Ticket::whereIn('area_id', $areasUsuario)->where('estado_id', $estadoResuelto->id)->get();

        return view('myViews.Admin.tickets.resueltos')->with('tickets', $ticketsResueltos) ;
    }

    public function tickets_cerrados()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
       
        $estadoCerrado= Estado::where('nombre', 'Cerrado')->first();

        $ticketsCerrados = Ticket::whereIn('area_id', $areasUsuario)->where('estado_id', $estadoCerrado->id)->get();

        return view('myViews.Admin.tickets.cerrados')->with('tickets', $ticketsCerrados) ;

    }

    public function tickets_reAbiertos()
    {
        $usuario= Auth::user();
        $areasUsuario=$usuario->areas()->pluck('area_id');
        
        $estado_reAbierto = Estado::where('nombre', 'Reabierto')->first();
        // Tickets que pertenecen a las areas del usuario auth con estado "Abierto"
        $tickets_reAbierto = Ticket::whereIn('area_id', $areasUsuario)->where('estado_id', $estado_reAbierto->id)->get();

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
    
        // ASIGNAR TICKET A USUARIO SELECCIONADO
 
        $previousUrl = url()->previous();   // Url anteriormente visitada
        $ticketId = basename($previousUrl); // Obtenemos el id del ticket de la url anteriormente visitada
       
        $ticket=Ticket::find($ticketId);  // Obtenemos el ticket por el Id anteriormente obtenido

        $ticket->asignado_a =$usuario->name ;  // asignamos el nombre del usuario tecnico seleccionado
        $ticket->estado_id= 2;  // cambiar estado a "abierto"
        $ticket->save();  // Guardamos

        // Notificacion al correo
        event(new TicketAsignadoCorreoEvent($ticket, $usuario));

        // Notificacion al telegram  
        $ticketLink = route('detalles_ticket', ['idTicket' => $ticket->id]); // Asumimos que tienes una ruta definida para ver el detalle del ticket
        $message = "Se te ha asignado un ticket: {$ticket->asunto}: ({$ticketLink})";
        $telegramService = app(TelegramService::class);
        $response = $telegramService->sendMessage($usuario->telegram_id, $message);
        

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

    
    public function volverDetalles($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if ($ticket->technician_id) {
            return response()->json(['assigned' => true]);
        } else {
            return response()->json(['assigned' => false]);
        }
    }


    public function todos_tecnicos(){

        $usuario=Auth::user();

        if ($usuario->hasRole(['Administrador'])) {
            $tecnicos = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Jefe de área', 'Técnico de soporte']);
            })->get();

            return view('myViews.Admin.tickets.agentes_tecnicos', compact('tecnicos'));

        }elseif($usuario->hasRole(['Jefe de área', 'Técnico de soporte'])){

            // Obtener las áreas al que pertenece el usuario autenticado
            $areasUsuario = $usuario->areas()->pluck('area_id');

            // Filtrar los técnicos por las áreas obtenidas
            $tecnicos = User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Jefe de área', 'Técnico de soporte']);
            })
            ->whereHas('areas', function ($query) use ($areasUsuario) {
                $query->whereIn('area_id', $areasUsuario);
            })
            ->get();

            return view('myViews.tecnicoSop.agentesArea', compact('tecnicos'));
        }
    }

    public function agentes_area($areaId){

        $area= Area::find($areaId);
        
        // Tecnicos de area con rol jefe de area o tecnico de soporte
        $tecnicosArea=$area->users()
        ->whereHas('roles', function ($query) {
            $query->whereIn('name', ['Jefe de área', 'Técnico de soporte']);
        })
        ->get();



        return response()->json($tecnicosArea);

    }

    public function reasignar_ticket($idTicket){

        $usuario= Auth::user();
        $ticket= Ticket::find($idTicket);
        $areas=Area::all();

        if ($usuario->hasRole(['Administrador'])) {
            return view('myViews.Admin.tickets.reasignarTicket', compact('areas', 'ticket' ));
        }
        elseif($usuario->hasRole(['Jefe de área'])){
            return view('myViews.jefeArea.reasignarTicket', compact('areas', 'ticket' ));
        }
    }

    public function guardar_reasignacion(Request $request, $idTicket){

        $request->validate([
                'area' =>'required',
                'tecnico' =>'required',
            ],
            [
                'area.required' => 'El campo área es requerido',
                'tecnico.required' => 'El campo técnico de soporte es requerido',
            ]
        );

        $ticket=Ticket::find($idTicket);
        $tecnico=User::find($request->tecnico);

        $ticket->asignado_a=$tecnico->name;
        $ticket->updated_at= Carbon::now();
        $ticket->save();

        // Notiifcacion al correo
        event(new TicketReasignadoCorreoEvent($ticket, $tecnico));


        // Notificacion al telegram  
        $ticketLink = route('detalles_ticket', ['idTicket' => $ticket->id]); // Asumimos que tienes una ruta definida para ver el detalle del ticket
        $message = "Se te ha reasignado un ticket: {$ticket->asunto}: ({$ticketLink})";
        $telegramService = app(TelegramService::class);
        $response = $telegramService->sendMessage($tecnico->telegram_id, $message);

        return redirect()->back()->with('status', 'El ticket ha sido reasignado exitosamente!!');
    }

    public function mensajeReabierto($idTicket){
        $mensaje= Comentario::where('ticket_id', $idTicket)->latest()->first();
        return view('myViews.Admin.tickets.mensajeReabierto', compact('mensaje', 'idTicket' ));

    }


    public function getMessagesNews($ticketId)
    {
        // Obtener los mensajes asociados al ticket
        $mensajes = Mensaje::where('ticket_id', $ticketId)
            ->orderBy('created_at', 'asc') // Ordenar por fecha de creación
            ->get();

        $ticket=Ticket::find($ticketId);
        $usuarioTicket=$ticket->user_id;
      
        // Retornar los mensajes como JSON
        return response()->json([
            'messages' => $mensajes,
            'usuarioTicket' =>$usuarioTicket
        ]);

    }

    public function actualizarEstadoResuelto($ticketId)
    {
        $ticket=Ticket::find($ticketId);
        $ticket->estado_id = Estado::where('nombre', 'Resuelto')->first()->id;
        $ticket->save();
   
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
        $ticket = Ticket::find($id);

        //* ELIMINAR LA IMAGEN DEL TICKET (DE LA CARPETA)
        $rutaTicket_img = public_path('images/tickets/') . $ticket->imagen;  

        if (file_exists($rutaTicket_img)) // Verificar si existe un archivo asociado
        {
            File::delete($rutaTicket_img); // Eliminar el archivo
        } 

        //* ELIMINAR MENSAJES RELACIONADOS
        $ticket->mensajes()->delete(); // Eliminar todos los mensajes relacionados

        //* ELIMINAR TICKET
        $ticket->delete(); 

        return redirect()->route('tickets.index')->with('eliminar', 'ok');
  
    }



}
