@extends('layout.index')

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message') }}</strong>
        </div>
    @endif

    @if (session()->has('message2'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message2') }}</strong>
        </div>
    @endif
    <br>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('top10') }}">{{ __('Liked') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('top10.views') }}">{{ __('Views') }}</a>
        </li>
    </ul>

    <section class="resume-section" id="about">
        <div class="resume-section-content">
            <h1 class="mb-0">
                TOP 10
            </h1>
            @foreach($pictures as $picture)
                @if (  $picture->visible == 1 && $picture->accept == 1 )
                    @if (\App\Models\BlockedUser::where('user_id', $user->id)->where('blocks_user', $picture->user_id)->count() != 0)
                        <p class="lead mb-5">
                        <div class="row section-box">
                            <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded" >
                                <img src="{{ asset('/assets/img/lock.png')}}" class="img-fluid img-thumbnail" >
                                <br>
                                <a href="{{ route('profiles.about', ['name' => $picture->user ]) }}">{{ $picture->user }}</a>  | {{ $picture->name }}
                                <br>
                                <i class="fas fa-calendar-week"></i>: {{ $picture->created_at }}
                                | <i class="far fa-comment-alt"></i> {{ \App\Models\Comment::where('picture_id', $picture->id)->count() }}
                                | <i class="far fa-eye"></i> {{ $picture->views }}
                            </div>
                        </div>
                     @else
                            <p class="lead mb-5">
                            <div class="row section-box">
                                <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded" >
                                    <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                        <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-fluid img-thumbnail" >
                                                </a>
                                                    <br>
                                                        <a href="{{ route('profiles.about', ['name' => $picture->user ]) }}">
                                                    <img class="img-fluid img-responsive rounded-circle mr-2" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $picture->user])->pluck('avatar')->first() }}" alt="user" style="width: 25px; height: 25px;">
                                                <b>{{ $picture->user }}</b>
                                        </a>  | <b>{{ $picture->name }}</b>
                                    <br>
                                    <i class="fas fa-calendar-week" style="color: green"></i>: {{ $picture->created_at }}
                                    | <i class="far fa-comment-alt" style="color: orange"></i> {{ \App\Models\Comment::where('picture_id', $picture->id)->count() }}
                                    | <i class="far fa-eye" style="color: #3737ec"></i> {{ $picture->views }}
                                    <br>
                                    @if ($picture->likes()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                        <button type="submit" class="btn btn-outline-success px-3 like" style="float: left" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                                    @else
                                        <button type="submit" class="btn btn-success px-3 like" style="float: left" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                                    @endif
                                    @if ($picture->favorites()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                        <button type="submit" class="btn btn-outline-warning px-3 addFavorite" style="float: right" data-id="{{ $picture->id }}"><i class="far fa-star" aria-hidden="true"></i></button>
                                    @else
                                        <button type="submit" class="btn btn-warning px-3 removeFavorite" style="float: right" data-id="{{ $picture->id }}"><i class="fas fa-star" aria-hidden="true"></i></button>
                                    @endif
                                </div>
                            </div>
                        @endif
                     @endif
            @endforeach
        </div>
    </section>

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
                alert( "Juz polubione" );
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
