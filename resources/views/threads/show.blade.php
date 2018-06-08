@extends('layouts.app')

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="row justify-content-start">
            <div class="col-md-8">
                <div class="card" v-if="!editing">
                    <div class="card-header">

                        <img src="{{ $thread->owner->avatar_path }}" width="50" height="50">
                        <a href="{{ route('profile',$thread->owner) }}">{{ $thread->owner->name }}</a>
                        <span v-text="title"></span>

                        @can ('update', $thread)
                        <form method="POST" action="/threads/{{ $thread->channel->slug }}/ {{ $thread->id }}">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger">Delete</button>
                        </form>
                        @endcan

                    </div>
                    <div class="card-body" v-html="body">
                    </div>
                    <div class="card-footer" v-if="authorize('owns', thread)">
                        <button class="btn btn-xm primary" @click="editing = true">Edit</button>
                    </div>
                </div>
                <div class="card" v-else>
                    <div class="card-header">

                        <div class="form-group">

                            <input v-model="form.title" class="form-control" type="text">
                        </div>


                    </div>
                    <div class="card-body">
                        <div class="form-group">

                            <wysiwyg :value="form.body" v-model="form.body"></wysiwyg>
                        </div>
                    </div>
                    <div class="card-footer">

                        <div class="row justify-content-between">
                            <div class="col-6">
                                <button class="btn btn-xm primary" @click="update">Update</button>
                                <button class="btn btn-xm primary" @click="editing = false">Cancel</button>
                            </div>
                            <div class="col-2">
                                @can ('update', $thread)
                                    <form method="POST" action="/threads/{{ $thread->channel->slug }}/ {{ $thread->id }}">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                    </div>
                </div>
                <hr>

                <replies @removed="repliesCount--"></replies>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <p>Test header</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="#">{{ $thread->owner->name }}</a>
                                has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}
                            </div>
                            <div class="col-4">
                                <subscribe-button :data="{{ json_encode($thread->isSubscribed) }}"></subscribe-button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-primary" v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unlock' : 'Lock'"></button>
                            </div>
                        </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
