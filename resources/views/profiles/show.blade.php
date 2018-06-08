@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($activities as $date => $activity)
                <h3 class="page-header">
                    {{ $date }}
                </h3>
                @foreach ($activity as $record)

                    @if (view()->exists("profiles.activities.{$record->type }"))

                        @include("profiles.activities.{$record->type }", ['activity' => $record])
                    @endif

                @endforeach
            @endforeach
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <avatar-form :user="{{ $profileUser }}"></avatar-form>
                </div>

                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
@endsection
