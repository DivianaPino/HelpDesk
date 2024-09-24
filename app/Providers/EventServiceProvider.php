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
