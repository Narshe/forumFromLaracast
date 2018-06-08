<reply :attributes="{{ $reply }}" inline-template v-cloak>

    <div id="reply-{{$reply->id}}" class="card">
        <div class="card-header">
        
            <a href="{{ route('profile',$reply->owner) }}">{{ $reply->owner->name }}</a>
            said
            {{ $reply->created_at->diffForHumans() }}

            <favorite :reply="{{ $reply }}"></favorite>
            {{-- <form  method="POST" action="/replies/{{ $reply->id }}/favorites">
                {{ csrf_field() }}

                <button type="submit" class="btn btn-primary" {{ $reply->isFavorited() ? 'disabled' : ''}}>
                    {{ $reply->favorites_count }} - {{ str_plural('Favorite', $reply->favorites_count )}}
                </button>
            </form> --}}
            @can('update', $reply)
                <form  method="POST" action="/threads/{{$reply->thread->channel->slug}}/{{$reply->thread->id}}/{{$reply->id}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
                <button class="btn btn-warning btn-sm" @click="edit">Edit</button>
            @endcan
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea v-model="body" class="form-control" name="body"></textarea>
                </div>
                <button class="btn btn-warning btn-sm" @click="update">Update</button>
                <button class="btn btn-link btn-sm" @click="cancel">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>
    </div>

</reply>
