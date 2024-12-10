<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\TicketEvent;
use App\Listeners\TicketListener;
use App\Events\MensajeTecnicoEvent;
use App\Listeners\MensajeTecnicoListener;
use App\Events\MensajeClienteEvent;
use App\Listeners\MensajeClienteListener;
use App\Events\CalificacionEvent;
use App\Listeners\CalificacionListener;
use App\Events\TicketCorreoEvent;
use App\Listeners\TicketCorreoListener;
use App\Events\TicketAsignadoCorreoEvent;
use App\Listeners\TicketAsignadoCorreoListener;
use App\Events\TicketReasignadoCorreoEvent;
use App\Listeners\TicketReasignadoCorreoListener;
use App\Events\MsjTecnicoCorreoEvent;
use App\Listeners\MsjTecnicoCorreoListener;
use App\Events\MsjClienteCorreoEvent;
use App\Listeners\MsjClienteCorreoListener;
use App\Events\TicketResueltoCorreoEvent;
use App\Listeners\TicketResueltoCorreoListener;
use App\Events\CalificacionCorreoEvent;
use App\Listeners\CalificacionCorreoListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Registered::class => [
            'App\Listeners\RegisteredEvent',
        ],
        TicketEvent::class => [
            TicketListener::class,
        ],
        MensajeTecnicoEvent::class => [
            MensajeTecnicoListener::class,
        ],
        MensajeClienteEvent::class => [
            MensajeClienteListener::class,
        ], 
        CalificacionEvent::class => [
            CalificacionListener::class,
        ],
        TicketCorreoEvent::class => [
            TicketCorreoListener::class,
        ],
        TicketAsignadoCorreoEvent::class => [
            TicketAsignadoCorreoListener::class,
        ],
        TicketReasignadoCorreoEvent::class => [
            TicketReasignadoCorreoListener::class,
        ],
        MsjTecnicoCorreoEvent::class => [
            MsjTecnicoCorreoListener::class,
        ],
        MsjClienteCorreoEvent::class => [
            MsjClienteCorreoListener::class,
        ],
        TicketResueltoCorreoEvent::class => [
            TicketResueltoCorreoListener::class,
        ],
        CalificacionCorreoEvent::class => [
            CalificacionCorreoListener::class,
        ],




    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
