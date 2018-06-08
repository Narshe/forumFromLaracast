<?php

namespace App\Filters;



use App\User;

class ThreadFilter extends Filters
{

    protected $filters = ['by', 'popularity', 'unanswered'];

    /**
     * [by description]
     * @param  [string] $username
     * @return [QueryBuilder]
     */
    public function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * [popularity sort by thread popularity (Numbers of replies)
     * @return [QueryBuilder]
     */
    public function popularity()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    public function unanswered()
    {
        return $this->builder->doesntHave('replies');
    }
}
