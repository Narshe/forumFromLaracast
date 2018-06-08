<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\YouWereMentioned;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reply;
use App\User;


class NotifyMentionedUsers
{

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {

        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function($user) use ($event) {

                $user->notify(new YouWereMentioned($event->reply));
            })
        ;
    }
}
