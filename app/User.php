<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Thread;

class User extends Authenticatable
{
    use Notifiable;

    protected $casts = [
        'confirmed' => 'boolean'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirmation_token', 'confirmed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRouteKeyname()
    {
        return 'name';
    }

    /**
     * [threads]
     * @return HasMany threads
     */
    public function threads()
    {
        return $this->hasMany('App\Thread')->latest();
    }

    /**
     * [activities]
     * @return HasMany activities
     */
    public function activities()
    {
        return $this->hasMany('App\Activity');
    }

    /**
     * [visitedThreadCacheKey]
     * @param  Thread $thread [description]
     * @return String visitedThreadCacheKey
     */
    public function visitedThreadCacheKey(Thread $thread)
    {
        return sprintf("user.%s.visits.%s", $this->id, $thread->id);
    }

    /**
     * [read Caching a thread]
     * @param  Thread $thread
     */
    public function read(Thread $thread)
    {
        cache()->forever($this->visitedThreadCacheKey($thread), \Carbon\Carbon::now());
    }

    /**
     * [lastReply Get the last reply from a thread]
     * @return HasOne lastReply
     */
    public function lastReply()
    {
        return $this->hasOne('App\Reply')->latest();
    }

    /**
     * [getAvatarPathAttribute]
     * @param  String $avatar
     * @return String Path of user avatar
     */
    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ? "/storage/{$avatar}" : 'storage/avatar/default.jpg');
    }

    /**
     * [confirm Confirm a user account]
     */
    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;

        $this->save();
    }

    /**
     * [isAdmin]
     * @return boolean isAdmin
     */
    public function isAdmin()
    {
        return $this->name === 'Hirz';
    }
}
