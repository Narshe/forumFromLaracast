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

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type'  => $this->getActivityType($event),
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        return $event . '_' . strtolower((new \ReflectionClass($this))->getShortName());
    }
}
