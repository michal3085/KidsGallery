@extends('layout.index')

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message') }}</strong>
        </div>
    @endif

    <section class="resume-section" id="about">
        <div class="resume-section-content">
          <h2>{{ $pictures->user }}: </h2>
            <h1 class="mb-0">
                {{ $pictures->name }}
            </h1>
                    <p class="lead mb-5">
                    <div class="row section-box">
                            <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                                    <img src="{{ asset('/storage') . '/' . $pictures->file_path }}" class="img-thumbnail">
                                <br>
                                {{ __('Added.') }}: {{ $pictures->created_at }}
                                | {{ __('Edited') }} : {{ $pictures->updated_at }}
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
                <form action="{{ route('commnents.add', ['id' => $pictures->id]) }}" method="POST">
                    @csrf
                    <div class="coment-bottom bg-white p-2 px-4">
                        <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                            <img class="img-fluid img-responsive rounded-circle mr-2" src="{{ asset('assets/img/antos.png') }}" width="38">
                            <input type="text" class="form-control mr-3" name="comment" placeholder="{{ __('Add comment...') }}" required>
                            <button class="btn btn-primary" type="submit">{{ __('Comment') }}</button></div>
                </form>
                    @foreach($comments as $comment)
                        <div class="d-flex flex-row comment-row">
                            <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2" src="{{ asset('assets/img/avatar.png') }}" alt="user" width="50"></span></div>
                            <div class="comment-text w-100">
                                <h5>{{ $comment->user_name }}</h5>
                                <div class="comment-footer"> <span class="date">{{ $comment->created_at }}
                                    @if ( $comment->user_name == \Illuminate\Support\Facades\Auth::user()->name)
                                        </span><span class="action-icons"><i class="far fa-trash-alt comment_delete" data-id="{{ $comment->id }}"></i> </span>
                                    @else
                                        </span><span class="action-icons"><i class="fas fa-exclamation comment_report" data-id="{{ $comment->id }}"></i></span>
                                    @endif
                                </div>
                                <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="pagination justify-content-center">
                        {{ $comments->links() }}
                    </div>
                    </div>

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
            icon: 'warning',
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
            title: '{{ __('You want to report this comment?') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes, report that comment') }}',
            cancelButtonText: '{{ __('No, do not report') }}'
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
             Swal.fire('Ups', '{{ __('Something went wrong') }}', 'error');
            });
            }

        })
        });
    });


@endsection
