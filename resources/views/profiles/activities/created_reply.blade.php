@component('profiles.activities.activity')

    @slot('heading')
        {{ $profileUser->name }} replies to
        <a href="/threads/{{$activity->subject->thread->channel->slug}}/{{$activity->subject->thread->id}}">
            {{ $activity->subject->thread->title }}
        </a>
    @endslot

    @slot('body')
        <p>{{ $activity->subject->body }}</p>
    @endslot

@endcomponent
