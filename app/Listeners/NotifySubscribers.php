<?php

namespace App\Listeners;

use App\Events\ThreadRecievedNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySubscribers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadRecievedNewReply  $event
     * @return void
     */
    public function handle(ThreadRecievedNewReply $event)
    {
        $event->reply->thread->subscriptions
        ->where('user_id','!=',$event->reply->id)
        ->each
        ->notify($event->reply);
    }
}
