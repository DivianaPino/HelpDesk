<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comentario;
use App\Models\Ticket;
use Carbon\Carbon;

class ComentarioNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comentario $comentario)
    {
        $this->comentario= $comentario;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'comentario_id' => $this->comentario->id,
            'comentario_respuestaId' => $this->comentario->respuesta_id,
            'comentario_ticketId' => $this->comentario->ticket_id,
            'comentario_mensaje' => $this->comentario->mensaje,
            'comentario_satisfaccion' => $this->comentario->nivel_satisfaccion,
            'comentario_reAbrir' => $this->comentario->bool_reabrir? 'sí' : 'no',
            'comentario_fecha' => Carbon::now()->diffForHumans(),
        ];
    }
}
