<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Activity;
use App\Reply;
use App\Events\ThreadHasNewReply;
use Stevebauman\Purify\Facades\Purify;

class Thread extends Model
{

    use RecordsActivity;

    protected $fillable = [
        'title',
        'body',
        'channel_id',
        'user_id',
        'created_at',
        'updated_at',
        'best_reply_id',
        'locked'
    ];

    protected $with = ['owner', 'channel'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('reply_count', function($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function($thread) {
            $thread->replies->each(function($reply){
                $reply->delete();
            });
        });

    }

    public function replies()
    {
        return $this->hasMany(Reply::class);

    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * [addReply]
     * @param Array $reply
     * @return Reply $reply
     */
    public function addReply(array $reply)
    {

        $reply = $this->replies()->create($reply);

        event(new ThreadHasNewReply($reply));
    //    $this->notifySubscribers($reply);

        return $reply;
    }

    /**
     * [notifySubscribers description]
     * @param  Reply $reply
     * @return void
     */
    public function notifySubscribers(Reply $reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe()
    {
        $this->subscriptions()->create([
            'user_id' => auth()->id()
        ]);
    }

    public function unsubscribe()
    {
        $this->subscriptions()->where('user_id', auth()->id())->delete();
    }


    public function subscriptions()
    {
        return $this->hasMany('App\ThreadSubscription');
    }

    /**
     * [getIsSubscribedAttribute]
     * @return boolean
     */
    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function getBodyAttribute($body)
    {
         return Purify::clean($body);
    //     dd($this->body);
    }
    /**
     * [hasUpdatesFor]
     * @return boolean
     */
    public function hasUpdatesFor($user = null)
    {

        $user = $user ?: auth()->user();

        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function markBestReply($reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    public function visits()
    {
        return new Visits($this);
    }

    public function isLocked()
    {
        return !! $this->locked;
    }

}
