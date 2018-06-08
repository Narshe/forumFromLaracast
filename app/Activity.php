<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Activity extends Model
{
    protected $fillable = ['user_id', 'type', 'subject_id', 'subject_type', 'created_at', 'updated_at'];

    public function subject()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return static::belongsTo('App\User');
    }

    public static function feed(User $user)
    {
        return static
            ::where('user_id', $user->id)
            ->with('subject')
            ->latest()
            ->get()
            ->groupBy(function($activity) {
                
                return $activity->created_at->format('Y-m-d');
            })
        ;
    }
}
