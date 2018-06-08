<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use App\User;
use App\Rules\SpamFree;

use App\Http\Requests\CreatePostRequest;


class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param  integer $channel_id
     * @param  Thread $thread
     * @param  CreatePostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channel_id, Thread $thread, CreatePostRequest $request)
    {

        if ($thread->isLocked()) {

            return response('The thread is locked', 422);
        }

        if ($request->authorize())
        {
            return $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
                ])->load('owner');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate([
            'body' => ['Required', new SpamFree]
        ]);

        $reply->update(['body' => request('body')]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $thread  = $reply->thread;
        $channel = $thread->channel;

        $reply->delete();

        if (request()->expectsJson()) {

            return response(['status' => 'Reply deleted']);
        }
        return redirect("/threads/{$channel}/{$thread}")
            ->with('flash', 'Reply deleted')
        ;
    }

}
