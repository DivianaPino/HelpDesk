<?php

use Illuminate\Support\Facades\Route;

// General
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Dashboard\DashboardController;

// Admin
use App\Http\Controllers\Admin\Tickets\TicketsController;
use App\Http\Controllers\Admin\Usuarios\UsuariosController;
use App\Http\Controllers\Admin\Areas\AreasController;
use App\Http\Controllers\Admin\Prioridades\PrioridadesController;
use App\Http\Controllers\Admin\Analisis\AnalisisController;
use App\Http\Controllers\Admin\Comentarios\ComentarioController;

// Técnico de soporte
use App\Http\Controllers\TecnicoSop\MisTickets\MisTicketsController;

// Usuario Estándar
use App\Http\Controllers\usuarioEst\TicketsUsuario\TicketsUsuarioController;

// -Todos los Usuarios registrados
use App\Http\Controllers\Todos_Registrados\Notificaciones\ComentarioNotiController;
use App\Http\Controllers\Todos_Registrados\Notificaciones\TicketNotiController;
use App\Http\Controllers\Todos_Registrados\Notificaciones\MasInfoNotiController;
use App\Http\Controllers\Todos_Registrados\Notificaciones\RespMasInfoNotiController;
use App\Http\Controllers\Todos_Registrados\Notificaciones\RespuestaNotiController;


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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified', 
    'comentarioNoti',
    'ticketNoti',
    'masInfoNoti',
    'respMasInfoNoti',
    'respuestaNoti',
])->group(function () {


    Route::get('/dashboard', [DashboardController::class, 'index'] )->name('dashboard');

    // Rol: Admin
    Route::resource('/usuarios', UsuariosController::class)->names('usuarios');
    Route::resource('/tickets', TicketsController::class)->names('tickets');  
    Route::resource('/areas', AreasController::class)->names('areas');
    Route::resource('/prioridades', PrioridadesController::class)->names('prioridades');
    Route::get('/asignar_area/{id}', [UsuariosController::class, 'asignar_area'] )->name('asignar_area');
    Route::put('/actualizar_area/{id}', [UsuariosController::class, 'actualizar_area'])->name('actualizar_area');
    Route::get('/area/{areaId}/tecnicos', [AreasController::class, 'area_tecnicos'] )->name('area_tecnicos');

    // Rol: Usuario estandar
    Route::resource('/usuario/tickets', TicketsUsuarioController::class)->names('usuarios_tickets');
    Route::get('/ticket/{idticket}/historial', [TicketsUsuarioController::class, 'historial'])->name('historial');
    Route::get('/ticket/{idticket}/mensaje/{idmensaje}', [TicketsUsuarioController::class, 'verMensaje'])->name('ver_mensaje');
    Route::post('/respuesta/mas_info/ticket/{idTicket}/{idMasInfo}', [TicketsUsuarioController::class, 'respMasInfo'] )->name('resp_masInfo');
    Route::get('/ticket/{idticket}/respuesta/{idrespuesta}', [TicketsUsuarioController::class, 'verRespuesta'])->name('ver_respuesta');
    Route::post('/comentar/respuesta/{idrespuesta}/ticket/{idTicket}', [TicketsUsuarioController::class, 'comentar_Respuesta'])->name('comentar_Respuesta');
   
   

    // Rol: Técnico de soporte
    Route::get('/area_usuario/tickets', [TicketsController::class, 'area_tickets'] )->name('areaUsuario_tickets');
    Route::get('/misTickets', [MisTicketsController::class, 'misTickets_agenteTecnico'] )->name('misTickets');
    Route::get('/mis_tickets/abiertos', [MisTicketsController::class, 'tickets_abiertos'] )->name('misTickets_abiertos');
    Route::get('/mis_tickets/enEspera', [MisTicketsController::class, 'tickets_enEspera'] )->name('misTickets_enEspera');
    Route::get('/mis_tickets/enRevision', [MisTicketsController::class, 'tickets_enRevision'] )->name('misTickets_enRevision');
    Route::get('/mis_tickets/vencidos', [MisTicketsController::class, 'tickets_vencidos'] )->name('misTickets_vencidos');
    Route::get('/mis_tickets/resueltos', [MisTicketsController::class, 'tickets_resueltos'] )->name('misTickets_resueltos');
    Route::get('/mis_tickets/cerrados', [MisTicketsController::class, 'tickets_cerrados'] )->name('misTickets_cerrados');
    Route::get('/mis_tickets/reabiertos', [MisTicketsController::class, 'tickets_reAbiertos'] )->name('misTickets_reAbiertos');

    //  Roles: Admin, Jefe de área, soporte técnico 
    Route::get('/noasignados', [TicketsController::class, 'tickets_noasignados'] )->name('tickets_noasignados');
    Route::get('/detalles/{idTicket}', [TicketsController::class, 'detalles_ticket'] )->name('detalles_ticket');
    Route::put('/asignarTicket/{idTicket}', [TicketsController::class, 'asignar_ticket'] )->name('asignar_ticket');
    Route::get('/abiertos', [TicketsController::class, 'tickets_abiertos'] )->name('tickets_abiertos');
    Route::get('/form/respuesta/{idTicket}', [TicketsController::class, 'form_Respuestaticket'] )->name('form_Respuestaticket');
    Route::get('/respuesta/ticket/{idTicket}', [TicketsController::class, 'form_Respuestaticket'] )->name('form_Respuestaticket');
    Route::post('/respuesta/ticket/{idTicket}', [TicketsController::class, 'guardar_respuestaTicket'] )->name('guardar_respuestaTicket');
    Route::get('/masInformación/{idTicket}', [TicketsController::class, 'masInfo'] )->name('masInfo');
    Route::post('/masInformacion/ticket/{idTicket}', [TicketsController::class, 'guardar_masInfo'] )->name('guardar_masInfo');
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

    Route::get('/respuesta/{idmensaje}/mas_info/ticket/{idTicket}', [TicketsController::class, 'verRespCliente_masInfo'] )->name('verRespCliente_masInfo');
    
    Route::get('/ver/ticket/{idTicket}', [TicketsController::class, 'verTicket'] )->name('verTicket');
    Route::get('/historial/ticket/{ticket_id}', [TicketsController::class, 'historialTicket'] )->name('historial_ticket');

    Route::resource('comentarios', ComentarioController::class);
    
    Route::get('/analisis', [AnalisisController::class, 'index'] )->name('index');

    // Roles: Todos
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}/resp/{idResp}', [ComentarioNotiController::class, 'marcar_como_leida'] )->name('c_marcar_como_leida');
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}', [TicketNotiController::class, 'marcar_como_leida'] )->name('t_marcar_como_leida');
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}/masInfo', [MasInfoNotiController::class, 'marcar_como_leida'] )->name('mi_marcar_como_leida');
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}/respmasInfo', [RespMasInfoNotiController::class, 'marcar_como_leida'] )->name('rmi_marcar_como_leida');
    Route::get('/notificacion/{idNotificacion}/ticket/{idticket}/respuesta', [RespuestaNotiController::class, 'marcar_como_leida'] )->name('resp_marcar_como_leida');

});
