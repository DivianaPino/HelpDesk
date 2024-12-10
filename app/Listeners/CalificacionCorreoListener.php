<?php

namespace App\Listeners;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\calificacionMailable;

class CalificacionCorreoListener
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
        $calificacion = $event->calificacion;
        $tecnico = $event->tecnico;
        $idTicket = $event->idTicket;
        $this->sendNotificationQualification($calificacion, $tecnico, $idTicket);

    }


    private function sendNotificationQualification($calificacion, $tecnico, $idTicket)
    {
        
        $email = $tecnico->email;

      
        try {
            Mail::to($email)->send(new calificacionMailable($idTicket));
            return "Correo enviado a {$email}";
        } catch (\Exception $e) {
             \Log::error("Error al enviar correo al técnico: ", ['exception' => $e]);
            return "Error al enviar correo a {$email}: " . $e->getMessage();
        }
        
    }
}
