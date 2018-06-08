@foreach ($threads as $thread)
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h4>
                                <a href="/threads/{{ $thread->channel->slug }}/{{ $thread->id }}">

                                    @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                        <strong>
                                            {{ $thread->title }}
                                        </strong>
                                    @else
                                        {{ $thread->title }}
                                    @endif
                                </a>
                            </h4>
                        </div>
                        <div class="col-3">
                            <strong>
                                {{ $thread->replies_count }} -
                                {{ str_plural('Reply', $thread->replies_count) }}
                            </strong>
                        </div>
                        <div class="col-3">
                            Posted By:
                            <img src="{{ $thread->owner->avatar_path }}" width="50" height="50">
                            <a href="/profiles/{{ $thread->owner->name }}">{{ $thread->owner->name }}</a>
                        </div>
                    </div>


                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>{{ $thread->body }}</p>

                </div>
                <div class="card-footer">
                    <em>{{ $thread->visits()->count() }} Visits</em>
                </div>
            </div>
        </div>
    </div>
@endforeach
