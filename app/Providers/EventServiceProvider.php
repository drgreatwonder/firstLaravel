<?php

namespace App\Providers;

use App\Events\NewCustomerHasRegisteredEvent;
// use App\Listeners\WelcomeNewCustomerListener;
// use Illuminate\Support\Facades\Event;
// use Illuminate\Auth\Events\Registered;
// use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

     //below is the code for automatically generating listeners (\App\Listeners)
    protected $listen = [
        NewCustomerHasRegisteredEvent::class => [
            \App\Listeners\WelcomeNewCustomerListener::class,
            \App\Listeners\RegisterCustomerToNewsletter::class,
            \App\Listeners\NotifyAdminViaSlack::class,
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
