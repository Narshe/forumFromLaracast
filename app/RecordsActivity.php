<?php

namespace App;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        static::created(function($subject) {
            $subject->recordActivity('created');
        });

        static::deleting(function($model) {
            $model->activity()->delete();
        });
    }

    /**
     * [Record a new activity according to the event and the current authenticated user]
     * @param  [type] $event [description]
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type'  => $this->getActivityType($event),
        ]);
    }

    /**
     * [activity]
     * @return MorphMany activity
     */
    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        return $event . '_' . strtolower((new \ReflectionClass($this))->getShortName());
    }
}
