<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Calificacion;
use App\Models\Ticket;
use Carbon\Carbon;

class CalificacionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Calificacion $calificacion)
    {
        $this->calificacion= $calificacion;
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
            'calificacion_id' => $this->calificacion->id,
            'calificacion_idTicket' => $this->calificacion->ticket_id,
            'calificacion_satisfaccion' => $this->calificacion->nivel_satisfaccion,
            'calificacion_accion' => $this->calificacion->accion,
            'calificacion_comentario' => $this->calificacion->comentario,
            'calificacion_fecha' => Carbon::now()->diffForHumans(),
        ];
    }
}
