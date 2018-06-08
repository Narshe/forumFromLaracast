<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Filters\ThreadFilter;
use App\Thread;
use App\Channel;
use App\Trending;
use App\Rules\SpamFree;

class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilter $filters, Trending $trending)
    {

        $threads = Thread::latest()->filter($filters);

        if($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->paginate(5);

        if (request()->wantsJson()) {

            return $threads;
        }

        $trending = $trending->get();

        return view('threads.index', compact('threads', 'trending'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => ['required', new SpamFree],
            'body'  => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body'  => request('body')
        ]);

        return redirect("threads/{$thread->channel->slug}/{$thread->id}")
            ->with('flash', 'Thread created')
        ;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Channel $channel, Thread $thread, Trending $trending)
    {

        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->visits()->record();


        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(20)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);

        $data = $request->validate([
            'title' => ['required', new SpamFree],
            'body'  => ['required', new SpamFree],
        ]);

        $thread->update($data);

        return $thread;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        return redirect('/threads');
    }


}
