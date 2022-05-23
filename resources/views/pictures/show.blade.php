@extends('layout.index')

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message') }}</strong>
        </div>
    @endif
    <section class="resume-section" id="about">
        <div class="resume-section-content">
                <a href="{{ route('profiles.about', ['name' => $pictures->user  ]) }}">
                    <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $pictures->user])->pluck('avatar')->first() }}" alt="user" style="width: 40px; height: 40px;"><b>{{ $pictures->user }}: </b></span></div>
                        </a>
                  <b class="mb-0" style="font-size: 40px;">
                      {{ $pictures->name }}
                     </b>
                        @if ($place_likes == 1 && $place_views == 1)
                                <i class="fas fa-crown" style="font-size: 30px; color: #ffbb2c" data-toggle="tooltip" data-html="true" data-title="Ta praca jest na pierwszym miejscu w TOP10 - Polubien i Wyświetleń!"  data-delay='{"show":"500", "hide":"300"}'></i>
                            @else
                        @if ($top == 1)
                            <i class="fas fa-award" style="color: #cea807; font-size: 25px;" data-toggle="tooltip" data-html="true" data-title="Ta praca jest w <b>TOP10 ({{ $place_likes }})</b> pod względem <b>polubień</b>"  data-delay='{"show":"500", "hide":"300"}'></i>
                                @endif
                                        @if ($top_views == 1)
                                    <i class="fas fa-medal" style="color: #cea807; font-size: 25px;" data-toggle="tooltip" data-html="true" data-title="Ta praca jest w <b>TOP10 ({{ $place_views }})</b> pod względem <b>wyswietleń</b>"  data-delay='{"show":"500", "hide":"300"}'></i>
                                @endif
                            @endif
                            <p class="lead mb-5">
                    <div class="row section-box">
                @if ($pictures->visible == 0)
                    <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                        <i class="fas fa-unlock" style="font-size: 25px;"></i>
                        @else
                            <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                                @endif
                                    <img src="{{ asset('/storage') . '/' . $pictures->file_path }}" class="img-thumbnail">
                                <br>
                                <i class="fas fa-calendar-week"></i>: {{ $pictures->created_at }}
                                | <i class="far fa-comment-alt"></i> {{ \App\Models\Comment::where('picture_id', $pictures->id)->count() }}
                                | <i class="far fa-eye"></i> {{ $pictures->views }}
                            </div>
                    </div>
                    @if ($pictures->likes()->where('picture_id', $pictures->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                        <button type="submit" class="btn btn-outline-success px-3 like" data-id="{{ $pictures->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $pictures->likes()->where('picture_id', $pictures->id)->count() }}</button>
                    @else
                        <button type="submit" class="btn btn-success px-3 like" data-id="{{ $pictures->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $pictures->likes()->where('picture_id', $pictures->id)->count() }}</button>
                    @endif
                            <br>
                        <br>

                    @if (Auth::check())
                        @if (Auth::user()->name == $pictures->user)
                                <button type="submit" class="btn btn-outline-danger float-right delete" data-id="{{ $pictures->id }}" aria-hidden="true">
                                    {{ __('Delete') }}</button>


                            <a href="{{ route('pictures.edit', ['picture' => $pictures->id]) }}">
                                <button type="submit" class="btn btn-outline-success float-left">{{ __('Edit') }}</button>
                            </a>
                        @else

                        <a href="{{ route('pictures.report', ['id' => $pictures->id]) }}">
                            <button type="submit" class="btn btn-outline-danger float-right">{{ __('Report') }}</button>
                        </a>
                        @endif
                    @endif
                    </p>
                <br>
            <hr>
                {{ $pictures->comment }}
                <br>
                <hr>
            @if ($pictures->allow_comments == 1)
                <form action="{{ route('commnents.add', ['id' => $pictures->id]) }}" method="POST">
                    @csrf
{{--                    <div class="coment-bottom bg-white p-2 px-4">--}}
                        <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                            <img class="img-fluid img-responsive rounded-circle mr-2" src="{{ asset('/storage') . '/' . $user->avatar }}" style="width: 50px; height: 50px;">
                            <input type="text" class="form-control mr-3" name="comment" placeholder="{{ __('Add comment...') }}" required>
                            <button class="btn btn-outline-primary" style="height: 38px;" type="submit"><i class="fas fa-paper-plane"></i></button></div>
{{--                    </div>--}}
                </form>
                    @foreach($comments as $comment)
                        <div class="d-flex flex-row comment-row">
                            <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $comment->user_name])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                            <div class="comment-text w-100">
                                <a href="{{ route('profiles.about', ['name' => $comment->user_name ]) }}"><b></b>{{ $comment->user_name }}</b></a>
                                <div class="comment-footer"> <span class="date" style="font-size: 12px;">{{ $comment->created_at }}
                                    @if ( $comment->user_name == \Illuminate\Support\Facades\Auth::user()->name)
                                        </span><span class="action-icons"><i class="far fa-trash-alt comment_delete" data-id="{{ $comment->id }}"></i> </span>
                                    @else
                                        </span><span class="action-icons"><i class="fas fa-exclamation comment_report" data-id="{{ $comment->id }}"></i></span>
                                    @endif
                                </div>
                                <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                                    @if (\App\Models\CommentsLike::where('comment_id', $comment->id)->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->where('like', 1)->count() == 1)
                                        <i class="fas fa-thumbs-up get_comment_like" data-id="{{ $comment->id }}" style="color: green"></i>
                                            @else
                                                <i class="far fa-thumbs-up get_comment_like" data-id="{{ $comment->id }}" style="color: green"></i>
                                                    @endif
                                                        {{ \App\Models\CommentsLike::where('comment_id', $comment->id)->where('like', 1)->count() }} |
                                                    @if (\App\Models\CommentsLike::where('comment_id', $comment->id)->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->where('dislike', 1)->count() == 1)
                                                <i class="fas fa-thumbs-down get_comment_unlike" data-id="{{ $comment->id }}" style="color: red"></i>
                                            @else
                                        <i class="far fa-thumbs-down get_comment_unlike" data-id="{{ $comment->id }}" style="color: red"></i>
                                    @endif
                                {{ \App\Models\CommentsLike::where('comment_id', $comment->id)->where('dislike', 1)->count() }}
                            </div>
                        </div>
                        <hr>
                    @endforeach
                    <div class="pagination justify-content-center">
                        {{ $comments->links() }}
                    </div>
                    </div>
            @elseif ( $pictures->allow_comments == 0)
            <div class="d-flex flex-row comment-row">
                <div class="comment-text w-100">
                    <h5 class="text-center"><i class="fas fa-comment-slash"></i> {{ __('User has not agreed to comments') }}</h5>
                </div>
            </div>
            @endif

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
            alert( "Error:0001" );
        });
     });
    });

    $( function()  {
    $('.delete').click( function () {
        Swal.fire({
            title: '{{ __('You definitely want to delete this image') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes, delete the picture') }}',
            cancelButtonText: '{{ __('No, do not delete') }}'
        }).then((result) => {
            if (result.value) {
                    $.ajax({
                        method: "DELETE",
                        url: "/pictures/" + $(this).data("id")
                        // data: { name: "John", location: "Boston" }
                    })
                    .done(function( response ) {
                        Swal.fire({
                            title: '{{ __('Image has been removed') }}',
                            icon: 'success',
                            showCancelButtonText: false,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            window.location.href = "/pictures";
                        })

                    })
                    .fail(function( response ) {
                        Swal.fire('Ups', '{{ __('Something went wrong') }}', 'error');
                    });
            }
        })
    });
    });


    $( function()  {
        $('.comment_delete').click( function () {
            Swal.fire({
            title: '{{ __('You definitely want to delete this comment?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes, delete that comment') }}',
            cancelButtonText: '{{ __('No, do not delete') }}'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                method: "DELETE",
                url: "/comments/delete/" + $(this).data("id")
            })
            .done(function( response ) {
                Swal.fire({
                title: '{{ __('Comment has been removed') }}',
                icon: 'success',
                showCancelButtonText: true,
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.reload();
            })

            })
            .fail(function( response ) {
                Swal.fire('Ups', '{{ __('Something went wrong') }}', 'error');
            });
            }
            })
        });
    });

    $( function()  {
        $('.comment_report').click( function () {
            Swal.fire({
            title: '{{ __('Are you sure you want to submit this comment?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes, submit a comment') }}',
            cancelButtonText: '{{ __('No, dont report') }}'
        }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "GET",
            url: "/comments/report/" + $(this).data("id")
        })
            .done(function( response ) {
            Swal.fire({
                title: '{{ __('Comment has been reported') }}',
                icon: 'success',
                showCancelButtonText: true,
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.reload();
            })

        })
            .fail(function( response ) {
             Swal.fire('{{ __('Something went wrong.') }}', '{{ __('You probably report this comment before') }}', 'error');
            });
            }

        })
        });
    });

    $(function() {
    $('.get_comment_like').click( function () {
    $.ajax({
    method: "GET",
    url: "/comments/like/" + $(this).data("id")
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
    $('.get_comment_unlike').click( function () {
    $.ajax({
    method: "GET",
    url: "/comments/dislike/" + $(this).data("id")
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

    $(function () {
    $('[data-toggle="tooltip"]').tooltip()
    })


@endsection
