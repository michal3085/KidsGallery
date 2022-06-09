@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.about', ['name' => $other_user->name]) }}"><i class="fas fa-portrait"></i> {{ __('Profile') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.gallery', ['name' => $other_user->name]) }}"><i class="far fa-images"></i> {{ __('Gallery') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.comments', ['name' => $other_user->name]) }}"><i class="far fa-comment-alt"></i> {{ __('Comments') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.favorites', ['name' => $other_user->name]) }}"><i class="far fa-thumbs-up"></i> {{ __('Liked') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.following', ['name' => $other_user->name]) }}"><i class="fas fa-user-friends"></i> {{ __('Following') }} ({{ \App\Models\Follower::where('user_id', $other_user->id)->count() }})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.followers', ['name' => $other_user->name]) }}"><i class="fas fa-users"></i> {{ __('Followers') }} ({{ \App\Models\Follower::where('follow_id', $other_user->id)->count() }})</a>
            </li>
            @if ($other_user->id == $user->id)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profiles.info', ['name' => $user->name]) }}"><i class="fas fa-info-circle"></i> {{ __('Info') }} ({{ \App\Models\ModeratorAction::where('user_id', $user->id)->where('moderator_only', 0)->count() }})</a>
                </li>
            @endif
        </ul>
        <section class="resume-section" id="about">
            <div class="container portfolio">
                <div class="row">
                    <div class="col-md-12">

                    </div>
                </div>
                <div class="bio-info">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bio-image">
                                        <img src="{{ asset('/storage') . '/' . $other_user->avatar }}" alt="image" style="width: 315px; height: 315px;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bio-content">
                                 <b style="font-size: 35px;">{{  $other_user->name }} </b>
                                @if ($other_user->following()->where('follow_id', $user->id)->where('rights', 1)->count() == 1)
                                        <i class="far fa-eye" style="color: #43a20b; font-size: 30px;" data-toggle="tooltip" data-title="Użytkownik zezwala Tobie na oglądanie swoich ukrytych prac."  data-delay='{"show":"500", "hide":"300"}'></i>
                                @endif
                                <br>{{ __('With us for:') }} {{ now()->diffInDays($other_user->created_at) }} {{ __('days') }}
                                    <hr>
                                @if (\App\Models\User::where('name', $other_user->name)->where('active', 0)->count() != 0)
                                    <div class="alert alert-danger" role="alert">
                                        {{ __('That user was blocked, for breaking regulations.') }}
                                    </div>
                                @endif
                                @if (\App\Models\BlockedUser::where('user_id', $other_user->id)->where('blocks_user', $user->id)->count() == 1)
                                    <div class="alert alert-secondary" role="alert">
                                        <i class="fas fa-user-lock" data-toggle="tooltip" data-title="Użytkownik blokuje Cię."  data-delay='{"show":"500", "hide":"300"}'></i>
                                        {{ __('User is blocking you') }}
                                    </div>
                                @endif
                                @if ($user->id != $other_user->id)
                                    @if (\App\Models\BlockedUser::where('user_id', $user->id)->where('blocks_user', $other_user->id)->count() == 0)
                                        @if ($unwanted == 0)
                                            <a href="{{ route('messages.show', ['to' => $other_user->name]) }}"><button type="button" class="btn btn-outline-info"><i class="far fa-envelope"></i>@if ( auth()->user()->countNewMessagesFrom($other_user->name) != 0 ) {{ auth()->user()->countNewMessagesFrom($other_user->name) }}@endif </button></a>
                                        @elseif ($unwanted == 1)
                                            <button type="button" class="btn btn-outline-secondary renewConv" data-id="{{ $conversation }}"><i class="fas fa-comment-slash"></i> Odblokuj widomosci</button>
                                            <br><br>
                                        @endif
                                        @if ($user->following()->where('follow_id', $other_user->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                            <button type="submit" class="btn btn-outline-danger follow" data-id="{{ $other_user->id }}"><i class="fas fa-heart"></i> Dodaj do ulubionych</button>
                                        @else
                                            <button type="submit" class="btn btn-danger delete" data-id="{{ $other_user->id }}"><i class="fas fa-heart"></i> Obserwujesz</button>
                                        @endif
                                            <button type="submit" class="btn btn-outline-secondary blocks" data-id="{{ $other_user->id }}"><i class="fas fa-lock"></i> Zablokuj</button>
                                        @else
                                            <button type="submit" class="btn btn-secondary unblocks" data-id="{{ $other_user->id }}"><i class="fas fa-unlock"></i> Użytkownik zablokowany (Odblokuj)</button>
                                    @endif
                                @endif
                                <br><br>
                                <h6>{{ __('Caption') }}:</h6>
                                {{ $userdata->about }}
                            </div>
                            <br>
                            <div class="bio-content">
                                <h6>{{ __('City') }}: {{ $userdata->city }}</h6>
                            </div>
                            <div class="bio-content">
                                <h6>{{ __('Birth date') }}: {{ $userdata->birthdate }}</h6>
                            </div>
                        </div>
                    </div>
                    <br>
                    <section class="section about-section gray-bg" id="about">
                        <div class="counter">
                            <div class="row">
                                <div class="col-6 col-lg-3">
                                    <div class="count-data text-center">
                                        <h6 class="count h2" data-to="500" data-speed="500">{{ \App\Models\Picture::where('user_id', $other_user->id)->where('accept', 1)->where('visible', 1)->count() }}</h6>
                                        <p class="m-0px font-w-600">{{ __('Pictures') }}</p>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="count-data text-center">
                                        <h6 class="count h2" data-to="150" data-speed="150">{{ \App\Models\Comment::where('user_id', $other_user->id)->count() }}</h6>
                                        <p class="m-0px font-w-600">{{ __('Comments') }}</p>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="count-data text-center">
                                        <h6 class="count h2" data-to="850" data-speed="850">{{ \App\Models\Follower::where('user_id', $other_user->id)->count()  }}</h6>
                                        <p class="m-0px font-w-600">{{ __('Following') }}</p>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="count-data text-center">
                                        <h6 class="count h2" data-to="190" data-speed="190">{{ \App\Models\Follower::where('follow_id', $other_user->id)->count() }}</h6>
                                        <p class="m-0px font-w-600">{{ __('Followers') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    </div>
                </div>
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
            $('.renewConv').click( function () {
            $.ajax({
            method: "POST",
            url: "/messages/renew/conversation/" + $(this).data("id")
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
            $('.blocks').click( function () {
            $.ajax({
            method: "POST",
            contentType: "application/json; charset=utf-8",
            url: "/profile/block/" + $(this).data("id")
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
            $('.unblocks').click( function () {
            $.ajax({
            method: "POST",
            contentType: "application/json; charset=utf-8",
            url: "/profile/unblock/" + $(this).data("id")
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
