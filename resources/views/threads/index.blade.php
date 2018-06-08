@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            @include('threads._list')

            {{ $threads->render() }}
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Trending Threads
                </div>
                <div class="card-body">
                    @foreach ($trending as $thread)
                        <p>
                            <a href="{{ $thread->path }}">{{ $thread->title }}</a>
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
