<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\YouWereMentioned;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reply;
use App\User;


class NotifySubscribers
{

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        $event->reply->thread->notifySubscribers($event->reply);
    }
}
