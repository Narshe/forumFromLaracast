<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Registered;

use App\Events\ThreadHasNewReply;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

         ThreadHasNewReply::class => [
            'App\Listeners\NotifyMentionedUsers',
            'App\Listeners\NotifySubscribers'
        ],

        Registered::class => [
            'App\Listeners\SendEmailConfirmationRequest'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
