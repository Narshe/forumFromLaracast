<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

use App\Reply;

class ThreadSubscription extends Model
{
    protected $fillable = ['user_id', 'thread_id', 'created_at', 'updated_at'];

    public function user() {

        return $this->belongsTo('App\User');
    }

    public function notify(Reply $reply)
    {
        $this->user->notify(new ThreadWasUpdated($reply->thread, $reply));
    }
}
