<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ticketMailable;

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
        $areaId=$ticket->area_id;
        $tecnicos = User::whereHas('areas', function ($query) use ($areaId) {
            $query->where('area_id', $areaId);
        })->role(['Técnico de soporte', 'Jefe de área'])->get();
    
        $emails = $tecnicos->pluck('email');
        
        $results = [];

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ticketMailable($ticket));
                $results[] = "Correo enviado a {$email}";
            } catch (\Exception $e) {
                if ($e instanceof \Swift_TransportException && strpos($e->getMessage(), 'Failed to authenticate on SMTP server') !== false) {
                    // Error de autenticación SMTP, ignoramos este error y continuamos con el siguiente correo
                    continue;
                }
                $results[] = "Error al enviar correo a {$email}: " . $e->getMessage();
            }
        }
        // Retorna todos los resultados después de procesar todos los correos
        return $results;
        
    }
    
}
