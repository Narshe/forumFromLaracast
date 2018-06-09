<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reply extends Model
{
    use RecordsActivity;

    protected $fillable = ['body', 'user_id', 'thread_id', 'created_at', 'updated_at'];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    protected static function boot()
    {
        parent::boot();


        static::deleting(function($reply) {
            $reply->favorites->each(function($favorite){
                $favorite->delete();
            });
        });
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo('App\Thread');
    }

    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where('user_id', $attributes)->exists()) {

            $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where('user_id', $attributes)->get()->each(function($favorite) {
            $favorite->delete();
        });
    }


    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }


    public function isFavorited()
    {
        return (bool) $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>',$body);
    }

    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function path()
    {
        return "/threads/{$this->thread->channel->slug}/{$this->thread->id}#reply-{$this->id}";
    }
}
