@extends('layouts.app')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="/threads">

                {{ csrf_field() }}

                <div class="form-group">
                    <label for="channel_id">Choose a channel</label>
                    <select class="form-control" name="channel_id" id="channel_id">
                        <option value="">Choose one...</option>
                        @foreach ($channels as $channel)
                            <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : ''}}>
                                {{ $channel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="title">Titre</label>
                    <input class="form-control" type="text" name="title" id="title" required value="{{ old('title')}}">
                </div>

                <div class="form-group">
                    <label for="body">Contenu</label>
                    <wysiwyg name="body"></wysiwyg>
                    {{-- <textarea class="col-md-12 form-control" name="body" id="body" rows="10" required>{{ old('body')}}</textarea> --}}
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Poster</button>
                </div>

                @if (count($errors))
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </form>

        </div>
    </div>
@endsection
