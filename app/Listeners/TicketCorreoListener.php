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
        
        $tecnicos= User::role(['Tecnico de soporte', 'Jefe de área'])->get();
        $emails = $tecnicos->pluck('email');

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ticketMailable($ticket));
                return "Correo enviado a {$email}";
            } catch (\Exception $e) {
                return "Error al enviar correo a {$email}: " . $e->getMessage();
            }
        }
    }
    
}
