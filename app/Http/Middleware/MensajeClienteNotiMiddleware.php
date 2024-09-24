<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Todos_Registrados\Notificaciones\MensajeClienteNotiController;

class MensajeClienteNotiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $notificacionesNoLeidas = $user->unreadNotifications;
    
        if ($request->ajax()) {
            return response()->json(['count' => $notificacionesNoLeidas->count()]);
        }
    
        if (Auth::check()) {
            try {
                $controller = new MensajeClienteNotiController;
                $controller->marcar_como_leida($request->idNotificacion, $request->idTicket);
            } catch (\Exception $e) {
                Log::error("Error marcando notificación como leída: " . $e->getMessage(), [
                    'exception' => $e,
                ]);
            }
        }
    
        view()->share('notificacionesNoLeidas', $notificacionesNoLeidas);
    
        return $next($request);
    }
    
    
}
