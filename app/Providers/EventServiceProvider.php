<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ComentarioEvent;
use App\Listeners\ComentarioListener;
use App\Events\TicketEvent;
use App\Listeners\TicketListener;
use App\Events\MasInfoEvent;
use App\Listeners\MasInfoListener;
use App\Events\RespMasInfoEvent;
use App\Listeners\RespMasInfoListener;
use App\Events\RespuestaEvent;
use App\Listeners\RespuestaListener;

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
        ComentarioEvent::class => [
            ComentarioListener::class,
        ],
        TicketEvent::class => [
            TicketListener::class,
        ],
        MasInfoEvent::class => [
            MasInfoListener::class,
        ],
        RespMasInfoEvent::class => [
            RespMasInfoListener::class,
        ],
        RespuestaEvent::class => [
            RespuestaListener::class,
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
