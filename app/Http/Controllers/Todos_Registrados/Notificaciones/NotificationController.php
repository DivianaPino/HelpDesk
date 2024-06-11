<?php

namespace App\Http\Controllers\Todos_Registrados\Notificaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = Notification::all(); // Asume que tienes un modelo Notification
        return response()->json($notifications);
    }
}
