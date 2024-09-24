<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = Notification::all(); // Asume que tienes un modelo Notification
        return response()->json($notifications);
    }



    public function actualizarContador(Request $request)
    {
        $userId = Auth::id();
        $updateToken = $request->header('X-CSRF-TOKEN');

        Redis::set("notificaciones_no_leidas:$userId", 0);

        return response()->json(['success' => true]);
    }

    public function obtenerNotificaciones()
    {
        $notifications = Notification::where('user_id', auth()->id())->get();
        return response()->json($notifications);
    }

}
