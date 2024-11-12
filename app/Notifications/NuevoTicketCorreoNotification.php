<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NuevoTicketCorreoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return $this->enviarEmailToSupportTeam();
    }

    private function enviarEmailToSupportTeam()
    {
        return (new MailMessage())
            ->subject('Nuevo ticket creado')
            ->line('Se ha creado un nuevo ticket:')
            ->line('Asunto: ' . $this->ticket->asunto)
            ->line('Descripción: ' . $this->ticket->mensaje)
            // ->action('Ver detalles del ticket', url('/tickets/' . $this->ticket->id))
            ->line('Fecha de creación: ' . $this->ticket->created_at);
    }



    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
