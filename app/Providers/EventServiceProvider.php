<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\ConversationStarted' => [
            'App\Listeners\ListenerConversationStarted',
        ],
        'App\Events\GameStarted' => [
            'App\Listeners\ListenerGameStarted',
        ],
        'App\Events\ProcessedGame' => [
            'App\Listeners\ListenerProcessedGame',
        ],
        'App\Events\MessageSended' => [
            'App\Listeners\ListenerMessageSended',
        ]

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
