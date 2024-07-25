<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Todos_Registrados\Notificaciones\TicketNotiController;

class TicketNotiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $notificacionesNoLeidas = $user->unreadNotifications;

        if (Auth::check()) { // Verificar si el usuario está autenticado
            try {

                // instancia del controlador
                $controller = new TicketNotiController;
                // Llamar al método marcar_como_leida
                // NOTA: los parametros extraidos del $request deben estar escritos igual que en la ruta que ejecuta este metodo
                $controller->marcar_como_leida($request->idNotificacion, $request->idticket);
            } catch (\Exception $e) {
                //Manejar la excepción si algo sale mal
                Log::error("Error marcando notificación como leída: ". $e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        }
    
        view()->share('notificacionesNoLeidas', $notificacionesNoLeidas);

 
        // return view('myViews.tecnicoSop.comentarios', compact('idTicket', 'ticket', 'respuesta', 'comentario'));  
        return $next($request);
    }
}
