@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.about', ['name' => $other_user->name]) }}"><i class="fas fa-portrait"></i> {{ __('Profile') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.gallery', ['name' => $other_user->name]) }}"><i class="far fa-images"></i> {{ __('Gallery') }}</a>
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
{{-- backgroundstripes--}}
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <h1 class="mb-0">
                </h1>
                @foreach($pictures as $picture)
                    <p class="lead mb-5">
                    <div class="row section-box">
                        @if ($picture->visible == 0)
                        <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                            <i class="fas fa-unlock" style="font-size: 25px;"></i><br>
                            @else
                                <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                                    @endif
                                <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                            </a>
                            <br>
                            <a href="{{ route('profiles.about', ['name' => $picture->user ]) }}">
                                <img class="img-fluid img-responsive rounded-circle mr-1" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $picture->user])->pluck('avatar')->first() }}" alt="user" style="width: 30px; height: 30px;">
                                <b>{{ $picture->user }}</b></a> | <b>{{ $picture->name }}</b>
                            <br>
                                    <i class="fas fa-calendar-week" style="color: green"></i>: {{ $picture->created_at }}
                                    | <i class="far fa-comment-alt" style="color: orange"></i> {{ \App\Models\Comment::where('picture_id', $picture->id)->count() }}
                                    | <i class="far fa-eye" style="color: #3737ec"></i> {{ $picture->views }}
                                    <br>
                                    @if ($picture->likes()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                        <button type="submit" class="btn btn-outline-success px-3 like" style="float: left" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                                    @else
                                        <button type="submit" class="btn btn-success px-3 unlike" style="float: left" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                                    @endif
                                    @if ($picture->favorites()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                        <button type="submit" class="btn btn-outline-warning px-3 addFavorite" style="float: right" data-id="{{ $picture->id }}"><i class="far fa-star" aria-hidden="true"></i></button>
                                    @else
                                        <button type="submit" class="btn btn-warning px-3 removeFavorite" style="float: right" data-id="{{ $picture->id }}"><i class="fas fa-star" aria-hidden="true"></i></button>
                                    @endif
                        </div>
                    </div>
                @endforeach
                {{--            <div class="pagination pagination-lg justify-content-center">--}}
                <div class="pagination justify-content-center">
                    {{ $pictures->links() }}
                </div>
            </div>
        </section>
    </div>

@endsection
@section('javascript')
$(function() {
$('.like').click( function () {
$.ajax({
method: "POST",
url: "/newlike/" + $(this).data("id")
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
$('.unlike').click( function () {
$.ajax({
method: "POST",
url: "/unlike/" + $(this).data("id")
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
$('.addFavorite').click( function () {
$.ajax({
method: "POST",
url: "/add/favorite/" + $(this).data("id")
// data: { name: "John", location: "Boston" }
})
.done(function( response ) {
window.location.reload();
})
.fail(function( response ) {
alert( "Juz polubione" );
});
});
});

$(function() {
$('.removeFavorite').click( function () {
$.ajax({
method: "POST",
url: "/remove/favorite/" + $(this).data("id")
// data: { name: "John", location: "Boston" }
})
.done(function( response ) {
window.location.reload();
})
.fail(function( response ) {
alert( "Juz polubione" );
});
});
});
@endsection
