<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->update(['locked' => true]);

        return response("The thread has been locked");
        // return back()->with('flash', 'The thread has been locked');
    }


    public function delete(Thread $thread)
    {
        $thread->update(['locked' => false]);

        return response("The thread has been unlocked");
    }
}
