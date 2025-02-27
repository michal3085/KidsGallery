@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.about', ['name' => $other_user->name]) }}">{{ __('Profile') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.gallery', ['name' => $other_user->name]) }}">{{ __('Gallery') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.comments', ['name' => $other_user->name]) }}">{{ __('Comments') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.favorites', ['name' => $other_user->name]) }}">{{ __('Liked') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.following', ['name' => $other_user->name]) }}">{{ __('Following') }} ({{ \App\Models\Follower::where('user_id', $other_user->id)->count() }})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.followers', ['name' => $other_user->name]) }}">{{ __('Followers') }} ({{ \App\Models\Follower::where('follow_id', $other_user->id)->count() }})</a>
            </li>
            @if ($other_user->id == $user->id)
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('profiles.info', ['name' => $other_user->name]) }}">{{ __('Info') }} ({{ \App\Models\ModeratorAction::where('user_id', $user->id)->where('moderator_only', 0)->count() }})</a>
                </li>
            @endif
        </ul>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('message') }}</strong>
            </div>
        @endif

        <section class="resume-section" id="about">
            <div class="resume-section-content">

                <div class="row section-box">
                    <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                        <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                        <br>
                        <i class="fas fa-calendar-week"></i>: {{ $picture->created_at }}
                        | <i class="far fa-eye"></i> {{ $picture->views }}
                    </div>
                </div>
                <h5><p class="text-center">{{__('Reason')}}:</p></h5>
                <div class="row">
                    {{ $info->reason }}
                </div>
                    <hr>
                @if ($info->moderator_response != NULL)
                <h5><p class="text-center">{{__('Moderator Answer')}}:</p></h5>
                        <div class="row">
                                {{ $info->moderator_response }}
                        </div>
                    <hr>
                @endif
                <h5><p class="text-center">{{__('Yours Answer')}}:</p></h5>
                    <form action="{{ route('banned.answer') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" id="user_answer" name="user_answer" rows="5">{{ $info->user_response }}</textarea>
                        </div>
                        <input type="hidden" id="info" value="{{$info->id}}" name="info">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Send answer') }}</button>
                    </form>

            </div>
        </section>

        @endsection
        @section('javascript')

            $(function() {
            $('.follow').click( function () {
            $.ajax({
            method: "POST",
            url: "/followers/add/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })
            .fail(function( response ) {
            alert( "Error:0001" );
            });
            });
            });

            $(function() {
            $('.delete').click( function () {
            $.ajax({
            method: "DELETE",
            contentType: "application/json; charset=utf-8",
            url: "/followers/delete/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })
            .fail(function( response ) {
            alert( "Error:0001" );
            });
            });
            });

            $(function() {
            $('.rightson').click( function () {
            $.ajax({
            method: "POST",
            url: "/followers/add/rights/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })
            .fail(function( response ) {
            alert( "Error:0001" );
            });
            });
            });

            $(function() {
            $('.rightsdel').click( function () {
            $.ajax({
            method: "DELETE",
            contentType: "application/json; charset=utf-8",
            url: "/followers/delete/rights/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })
            .fail(function( response ) {
            alert( "Error:0001" );
            });
            });
            });
@endsection
