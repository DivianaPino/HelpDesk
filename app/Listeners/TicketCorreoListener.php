<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ticketMailable;
use App\Models\User;
use App\Models\Area;

class TicketCorreoListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
       
        $ticket = $event->ticket;
        $this->sendNotificationToSupportTeam($ticket);
        
    }

    private function sendNotificationToSupportTeam($ticket)
    {
        $area = Area::find($ticket->area_id);
        $notifCorreo = $area->notif_correo;

        $roles = $notifCorreo === "Todos" 
            ? ['Administrador', 'Técnico de soporte', 'Jefe de área'] 
            : ['Administrador', 'Jefe de área'];

        $tecnicos = User::whereHas('areas', function ($query) use ($ticket) {
            $query->where('area_id', $ticket->area_id);
        })->role($roles)->pluck('email');

        foreach ($tecnicos as $email) {
            try {
                Mail::to($email)->send(new ticketMailable($ticket));
                
            } catch (\Exception $e) {
                if ($e instanceof \Swift_TransportException && strpos($e->getMessage(), 'Failed to authenticate on SMTP server') !== false) {
                    // Ignorar error de autenticación
                    continue;
                }
                // Registrar el error en lugar de acumular mensajes
                \Log::error("Error al enviar correo a {$email}: " . $e->getMessage());
            }
        }
   
        
        // Retorna todos los resultados después de procesar todos los correos
        //return $results;
        
    }
    
}
