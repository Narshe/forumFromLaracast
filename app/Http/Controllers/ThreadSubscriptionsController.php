<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread;

class ThreadSubscriptionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channel, Thread $thread)
    {
        $thread->subscribe();

    }

    public function destroy($channel, Thread $thread)
    {
        $thread->unsubscribe();
    }
}
