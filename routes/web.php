<?php

use Illuminate\Support\Facades\Route;

// General
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Todos_Registrados\Notificaciones\NotificationController;

// Admin
use App\Http\Controllers\Admin\Tickets\TicketsController;
use App\Http\Controllers\Admin\Usuarios\UsuariosController;
use App\Http\Controllers\Admin\Areas\AreasController;
use App\Http\Controllers\Admin\Prioridades\PrioridadesController;
use App\Http\Controllers\Admin\Servicios\ServiciosController;
use App\Http\Controllers\Admin\Analisis\AnalisisController;
use App\Http\Controllers\Admin\Grafico\GraficoController;
use App\Http\Controllers\Admin\Reportes\ReporteController;

// Técnico de soporte
use App\Http\Controllers\TecnicoSop\MisTickets\MisTicketsController;

// Usuario Estándar
use App\Http\Controllers\usuarioEst\TicketsUsuario\TicketsUsuarioController;

// -Todos los Usuarios registrados
use App\Http\Controllers\Todos_Registrados\Notificaciones\CalificacionNotiController;
use App\Http\Controllers\Todos_Registrados\Notificaciones\TicketNotiController;
use App\Http\Controllers\Todos_Registrados\Notificaciones\MensajeTecnicoNotiController;
use App\Http\Controllers\Todos_Registrados\Notificaciones\MensajeClienteNotiController;
use App\Http\Controllers\Admin\Calificaciones\CalificacionController;

// Notificaciones Email
use App\Mail\ticketMailable;

use App\Services\TelegramService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'] )->name('/');

Route::get('ticketEstado/{idTicket}', [TicketsUsuarioController::class, 'ticketEstado'])->name('ticketEstado');
Route::get('nivelSatisfaccion/{idTicket}', [CalificacionController::class, 'nivel_satisfaccion'] )->name('nivelSatisfaccion');
Route::get('/tickets/{idTicket}/mensajes', [TicketsController::class, 'getMessagesNews'])->name('mensajes.nuevos');


// Route::get('/emailTicket', function(){
//   Mail::to('divianap96@gmail.com')->send(new ticketMailable);
//   return "Mensaje enviado!";
// })->name('ticket.email');


Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
  'calificacionNoti',
])->group(function () {

  Route::middleware(['role:Administrador'])->group(function () {
    Route::resource('/usuarios', UsuariosController::class)->names('usuarios');
    Route::put('/actualizar_area/{id}', [UsuariosController::class, 'actualizar_area'])->name('actualizar_area');
  });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified', 
    'ticketNoti',
    'mensajeClienteNoti',
    'mensajeTecnicoNoti',    
    'calificacionNoti',
])->group(function () {


  Route::get('/dashboard', [DashboardController::class, 'index'] )->name('dashboard');

  // Rol: Admin
  Route::middleware(['role:Administrador'])->group(function () {
    Route::resource('/tickets', TicketsController::class)->names('tickets');
    Route::resource('/areas', AreasController::class)->names('areas');
    Route::resource('/servicios', ServiciosController::class)->names('servicios');
    Route::resource('/prioridades', PrioridadesController::class)->names('prioridades');
    Route::get('/asignar_area/{id}', [UsuariosController::class, 'asignar_area'] )->name('asignar_area');
    Route::get('/area/{areaId}/tecnicos', [AreasController::class, 'area_tecnicos'] )->name('area_tecnicos');
    Route::get('/analisis', [AnalisisController::class, 'index'] )->name('indexAnalisis');
    Route::get('/grafico_kpi', [GraficoController::class, 'index'] )->name('indexGrafico');
    Route::get('/reporte/tickets', [ReporteController::class, 'reporte'] )->name('reporteTickets');
    Route::post('/tickets/filtrados', [ReporteController::class, 'reporteFiltrados'] )->name('reporteFiltrados');
    Route::post('/reporte-pdf',  [ReporteController::class, 'reporteCompletoPDF'] )->name('reporteCompletoPDF');
    Route::post('/reporteRango-pdf',  [ReporteController::class, 'reporteRangoPDF'] )->name('reporteRangoPDF');
    Route::get('/area/{areaId}/servicios', [AreasController::class, 'area_servicios'] )->name('area_servicios');
    Route::get('/crear/servicio/area/{areaId}', [ServiciosController::class, 'crear_servicio'] )->name('crear_servicio');
    Route::post('/guardar/servicio/{areaId}',  [ServiciosController::class, 'guardar_servicio'] )->name('guardar_servicio');

  });


    // Rol: Administrador, Usuario estandar
    Route::middleware(['role.any:Administrador,Usuario estándar'])->group(function () {
      Route::resource('/usuario/tickets', TicketsUsuarioController::class)->names('usuarios_tickets');
      Route::get('/ticket/reportado/{idTicket}', [TicketsUsuarioController::class, 'ver_ticketReportado'])->name('ver_ticketReportado');
      // *Mostrar los servicios pertenencientes al area (Crear un ticket)
      Route::get('/servicios/area/{idarea}', [TicketsUsuarioController::class, 'servicios_area'] )->name('servicios_area');
      Route::post('/mensaje/cliente/ticket/{idTicket}', [TicketsUsuarioController::class, 'guardar_mensajeCliente'] )->name('guardar_mensajeCliente');
    
    });

    // Rol: Administrador,Jefe de área,Técnico de soporte
    Route::middleware(['role.any:Administrador,Jefe de área,Técnico de soporte'])->group(function () {
      Route::get('/misTickets', [MisTicketsController::class, 'misTickets_agenteTecnico'] )->name('misTickets');
      Route::get('/area_usuario/tickets', [TicketsController::class, 'area_tickets'] )->name('areaUsuario_tickets');
      Route::get('/mis_tickets/abiertos', [MisTicketsController::class, 'tickets_abiertos'] )->name('misTickets_abiertos');
      Route::get('/mis_tickets/enEspera', [MisTicketsController::class, 'tickets_enEspera'] )->name('misTickets_enEspera');
      Route::get('/mis_tickets/enRevision', [MisTicketsController::class, 'tickets_enRevision'] )->name('misTickets_enRevision');
      Route::get('/mis_tickets/vencidos', [MisTicketsController::class, 'tickets_vencidos'] )->name('misTickets_vencidos');
      Route::get('/mis_tickets/resueltos', [MisTicketsController::class, 'tickets_resueltos'] )->name('misTickets_resueltos');
      Route::get('/mis_tickets/cerrados', [MisTicketsController::class, 'tickets_cerrados'] )->name('misTickets_cerrados');
      Route::get('/mis_tickets/reabiertos', [MisTicketsController::class, 'tickets_reAbiertos'] )->name('misTickets_reAbiertos');

      Route::get('/noasignados', [TicketsController::class, 'tickets_noasignados'] )->name('tickets_noasignados');
      Route::get('/detalles/{idTicket}', [TicketsController::class, 'detalles_ticket'] )->name('detalles_ticket');
      Route::put('/asignarTicket/{idTicket}', [TicketsController::class, 'asignar_ticket'] )->name('asignar_ticket');
      Route::get('/abiertos', [TicketsController::class, 'tickets_abiertos'] )->name('tickets_abiertos');
      Route::get('/form/mensaje/tec/ticket/{idTicket}', [TicketsController::class, 'form_msjTecnico'] )->name('form_msjTecnico');
      //Route::get('/respuesta/ticket/{idTicket}', [TicketsController::class, 'form_Respuestaticket'] )->name('form_Respuestaticket');
      Route::post('/mensaje/tecnico/ticket/{idTicket}', [TicketsController::class, 'guardar_mensajeTecnico'] )->name('guardar_mensajeTecnico');
      Route::get('/enEspera', [TicketsController::class, 'tickets_enEspera'] )->name('tickets_enEspera');
      Route::get('/enRevision', [TicketsController::class, 'tickets_enRevision'] )->name('tickets_enRevision');
      Route::get('/vencidos', [TicketsController::class, 'tickets_vencidos'] )->name('tickets_vencidos');
      Route::get('/resueltos', [TicketsController::class, 'tickets_resueltos'] )->name('tickets_resueltos');
      Route::get('/reabiertos', [TicketsController::class, 'tickets_reabiertos'] )->name('tickets_reabiertos');
      Route::get('/cerrados', [TicketsController::class, 'tickets_cerrados'] )->name('tickets_cerrados');
      Route::get('/tecnicos/tickets/asignados/{idTicket}', [TicketsController::class, 'tecnicos_tktAsignados'] )->name('tecnicos_tkt_asignados');
      Route::post('/asignar/tecnico/{user}/ticket', [TicketsController::class, 'asignar_ticket_a_tecnico'] )->name('asignar_tecnico_ticket');
  
      Route::get('/tickets/abiertos/tecnico/{user}', [TicketsController::class, 'tkt_abierto_tecnico'] )->name('tkt_abierto_tecnico');
      Route::get('/tickets/enEspera/tecnico/{user}', [TicketsController::class, 'tkt_enEspera_tecnico'] )->name('tkt_enEspera_tecnico');
  
      Route::get('/ver/ticket/{idTicket}', [TicketsController::class, 'verTicket'] )->name('verTicket');
    
      Route::get('/agentes_tecnicos', [TicketsController::class, 'todos_tecnicos'] )->name('todos_tecnicos');
  
      Route::get('/mensaje/reabierto/ticket/{idTicket}', [TicketsController::class, 'mensajeReabierto'] )->name('mensajeReabierto');
    });


    // Rol: Jefe de area
    Route::middleware(['role:Jefe de área'])->group(function () {
      Route::get('/calificaciones/tickets/jefeArea', [CalificacionController::class, 'calificaciones_tk_jefeArea'] )->name('calificaciones_tk_jefeArea');
    });

    // Roles: Admin y jefe de area 
    Route::middleware(['role.any:Administrador,Jefe de área'])->group(function () {
      Route::get('/area/{idarea}/agentes', [TicketsController::class, 'agentes_area'] )->name('agentes_area');
      Route::get('/reasignar/ticket/{idTicket}', [TicketsController::class, 'reasignar_ticket'] )->name('reasignar_ticket');
      Route::post('/guardar/reasignacion/ticket/{idTicket}', [TicketsController::class, 'guardar_reasignacion'] )->name('guardar_reasignacion');
    });

  
    // Roles: Todos

    Route::get('/calificaciones', [CalificacionController::class, 'calificaciones'] )->name('calificaciones');
    Route::get('/calificaciones/ticket/{idTicket}', [CalificacionController::class, 'calificaciones_ticketCliente'] )->name('calificacionesTicket');
    // Route::get('/calificacion_ticketCliente/{idCalificacion}', [CalificacionController::class, 'calificacion_ticketCliente'] )->name('calificacion');
   
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}', [CalificacionNotiController::class, 'marcar_como_leida'] )->name('c_marcar_como_leida');
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}', [TicketNotiController::class, 'marcar_como_leida'] )->name('t_marcar_como_leida');
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}/mensajeTecnico', [MensajeTecnicoNotiController::class, 'marcar_como_leida'] )->name('msjTecnico_marcar_como_leida');
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}/mensajeCliente', [MensajeClienteNotiController::class, 'marcar_como_leida'] )->name('msjCliente_marcar_como_leida');


    Route::post('/actualizar-notificaciones', [NotificationController::class, 'actualizarContador']);
    Route::get('/obtener-notificaciones', [NotificationController::class, 'obtenerNotificaciones']);

    Route::get('/acceso-denegado', function () {
      return view('errors.403');
    })->name('acceso.denegado');

  });



