<?php

namespace App\Providers;

use App\Events\DeletedPlayer;
use App\Events\TournamentCreated;
use App\Listeners\GenerateMatches;
use App\Listeners\ReplacePlayer;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        TournamentCreated::class => [
            GenerateMatches::class
        ],
        DeletedPlayer::class => [
            ReplacePlayer::class
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
