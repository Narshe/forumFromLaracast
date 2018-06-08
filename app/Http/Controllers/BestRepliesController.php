<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Reply;

class BestRepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $this->authorize('update', $reply->thread);

        $reply->thread->markBestReply($reply);
        
    }
}
