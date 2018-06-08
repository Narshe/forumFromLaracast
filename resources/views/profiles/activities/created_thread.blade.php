@component('profiles.activities.activity')

    @slot('heading')
        Thread
            <a href="/threads/{{$activity->subject->channel->slug}}/{{$activity->subject->id}}">
                {{ $activity->subject->title }}
            </a>
        created by {{ $profileUser->name }}
    @endslot

    @slot('body')
        <p>{{ $activity->subject->body }}</p>
    @endslot

@endcomponent
