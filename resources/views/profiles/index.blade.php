@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.info', ['name' => $other_user->name]) }}">{{ __('Info') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.gallery', ['name' => $other_user->name]) }}">{{ __('Gallery') }}</a>
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
                    <a class="nav-link" href="{{ route('profiles.followers', ['name' => $other_user->name]) }}">{{ __('Info') }} ({{ \App\Models\ModeratorAction::where('user_id', $user->id)->where('moderator_only', 0)->count() }})</a>
                </li>
            @endif
        </ul>

        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <h1 class="mb-0">
                </h1>
                @foreach($pictures as $picture)
                    @if ( $picture->visible == 0)
                        @if( \App\Models\Follower::where('user_id', $picture->user_id)->where('follow_id', $user->id)->where('rights', 1)->count() != 0 || $picture->user_id == \Illuminate\Support\Facades\Auth::id() )
                            <p class="lead mb-5" >
                            <div class="row section-box">
                                <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded" style="background-color: rgba(111,220,12,0.12);" >
                                    <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                        <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                                    </a>
                                    <br>
                                    <a href="{{ route('profiles.info', ['name' => $picture->user ]) }}">{{ $picture->user }}</a> | {{ $picture->name }}
                                    <br>
                                    <i class="fas fa-calendar-week"></i>: {{ $picture->created_at }}
                                    | <i class="far fa-eye"></i> {{ $picture->views }}
                                </div>
                            </div>
                            @if ($picture->likes()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                <button type="submit" class="btn btn-outline-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                            @else
                                <button type="submit" class="btn btn-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                            @endif
                        @endif
                    @endif
                    @if ($picture->visible == 1)
                        <p class="lead mb-5">
                        <div class="row section-box">
                            <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                                <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                    <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                                </a>
                                <br>
                                <a href="{{ route('profiles.info', ['name' => $picture->user ]) }}">{{ $picture->user }}</a>  | {{ $picture->name }}
                                <br>
                                <i class="fas fa-calendar-week"></i>: {{ $picture->created_at }}
                                | <i class="far fa-eye"></i> {{ $picture->views }}
                            </div>
                        </div>
                        @if ($picture->likes()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                            <button type="submit" class="btn btn-outline-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                        @else
                            <button type="submit" class="btn btn-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                        @endif
                    @endif
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
@endsection
