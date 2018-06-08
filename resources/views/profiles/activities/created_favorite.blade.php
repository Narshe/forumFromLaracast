@component('profiles.activities.activity')

    @slot('heading')
        {{ $profileUser->name }} favorite
        
        <a href="{{ $activity->subject->favorited->path() }}">
            Go to
        </a>
    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot

@endcomponent
