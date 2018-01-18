<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Registered;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    // to make ThreadRecievedNewReply() class und NotifyMentionedUsers(),.. follow steps:
    // 1- make $listen = ['App\Events\Th......NotifySubscribers],];
    // 2- write this command: php artisan event:generate
    // it will auto make classes ThreadRecievedNewReply and NotifyMentionedUsers() and NotifySubscribers
    protected $listen = [
        // one listner to mention users and other for subscribers
        'App\Events\ThreadRecievedNewReply'=>[
            'App\Listeners\NotifyMentionedUsers',
            'App\Listeners\NotifySubscribers'
        ],
        // for confirm email, explained in RegisterConfiramtionController/index
        Registered::class=>[
            'App\Listeners\SendEmailConfirmationRequest',
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
