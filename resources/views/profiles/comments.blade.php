@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.about', ['name' => $other_user->name]) }}"><i class="fas fa-portrait"></i> {{ __('Profile') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.gallery', ['name' => $other_user->name]) }}"><i class="far fa-images"></i> {{ __('Gallery') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.comments', ['name' => $other_user->name]) }}"><i class="far fa-comment-alt"></i> {{ __('Comments') }}</a>
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
            <div class="resume-section-content">
                    <div class="d-flex flex-row add-comment-section mt-4 mb-4"></div>
                @foreach($comments as $comment)
                    @if ( \App\Models\Picture::where('id', $comment->picture_id)->where('visible', 0)->count() == 1 )
                        @if( \App\Models\Follower::where('user_id', $comment->parent_id)->where('follow_id', $user->id)->where('rights', 1)->count() == 1 || $user->id == $comment->parent_id  )
                    <div class="d-flex flex-row comment-row">
                        <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $comment->user_name])->pluck('avatar')->first() }}" alt="user" style="height: 50px; width: 50px;"></span></div>
                        <div class="comment-text w-100">
                            <b>{{ $comment->user_name }}</b>
                            <div class="comment-footer"> <span class="date" style="font-size: 12px;">{{ $comment->created_at }}
                                    @if ( $comment->user_name == \Illuminate\Support\Facades\Auth::user()->name)
                                        </span><span class="action-icons"><i class="far fa-trash-alt comment_delete" data-id="{{ $comment->id }}"></i> </span>
                                        @else
                                             </span><span class="action-icons"><i class="fas fa-exclamation comment_report" data-id="{{ $comment->id }}"></i></span>
                                             @endif
                                <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                            </div>
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
                        <button type="button" class="btn btn-outline-success">
                            <a href="{{ route('pictures.show', ['picture' => $comment->picture_id]) }}">
                            <img class="img-fluid img-responsive  mr-2" src="{{ asset('/storage') . '/' . \App\Models\Picture::where(['id' => $comment->picture_id])->pluck('file_path')->first() }}" alt="user" style="height: 50px; width: 50px;">
                            </a>
                        </button>
                    </div>
                    <hr>
                        @endif
                    @endif
                        @if ( \App\Models\Picture::where('id', $comment->picture_id)->where('visible', 1)->count() == 1)
                            <div class="d-flex flex-row comment-row">
                                <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $comment->user_name])->pluck('avatar')->first() }}" alt="user" style="height: 50px; width: 50px;"></span></div>
                                <div class="comment-text w-100">
                                    <b>{{ $comment->user_name }}</b>
                                    <div class="comment-footer"> <span class="date" style="font-size: 12px;">{{ $comment->created_at }}
                                        @if ( $comment->user_name == \Illuminate\Support\Facades\Auth::user()->name)
                                            </span><span class="action-icons"><i class="far fa-trash-alt comment_delete" data-id="{{ $comment->id }}"></i> </span>
                                                @else
                                                    </span><span class="action-icons"><i class="fas fa-exclamation comment_report" data-id="{{ $comment->id }}"></i></span>
                                                        @endif
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
                                <button type="button" class="btn btn-outline-success">
                                    <a href="{{ route('pictures.show', ['picture' => $comment->picture_id]) }}">
                                        <img class="img-fluid img-responsive  mr-2" src="{{ asset('/storage') . '/' . \App\Models\Picture::where(['id' => $comment->picture_id])->pluck('file_path')->first() }}" alt="user" width="50">
                                    </a>
                                </button>
                            </div>
                            <hr>
                        @endif
                @endforeach
            <div class="pagination justify-content-center">
                {{ $comments->links() }}
            </div>
        </div>
    </section>


@endsection
        @section('javascript')
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



@endsection
