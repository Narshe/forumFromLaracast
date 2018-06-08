<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

use App\Reply;

class ThreadHasNewReply
{
    use SerializesModels;

    public $reply;

    /**
     * [__construct ]
     * @param Thread $thread
     * @param Reply  $reply
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

}
