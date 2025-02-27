@extends('moderator.app')

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message') }}</strong>
        </div>
    @endif

    <section class="resume-section" id="about">
        <div class="resume-section-content">
            <a href="{{ route('profiles.about', ['name' => $picture->user  ]) }}"><h2>{{ $picture->user }}: </h2></a>
            <h1 class="mb-0">
                {{ $picture->name }}
            </h1>
            <p class="lead mb-5">
            <div class="row section-box">
                <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                    <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
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
            <br>
            <br>
                <a href="{{ route('pictures.report', ['id' => $picture->id]) }}">
                   <button type="submit" class="btn btn-outline-danger float-right">{{ __('Block') }}</button>
                </a>

                    </p>
                    <br>
                    <hr>
                    {{ $picture->comment }}
                    <br>
                    <hr>
                    @if ($picture->allow_comments == 1)
                        <form action="{{ route('commnents.add', ['id' => $picture->id]) }}" method="POST">
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
                                    <a href="{{ route('profiles.about', ['name' => $comment->user_name ]) }}"><h5>{{ $comment->user_name }}</h5></a>
                                    <div class="comment-footer"> <span class="date">{{ $comment->created_at }}
                                        </span><span class="action-icons"><i class="far fa-trash-alt comment_delete" data-id="{{ $comment->id }}"></i> </span>
                                    </div>
                                    <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        @endforeach
                        <div class="pagination justify-content-center">
                            {{ $comments->links() }}
                        </div>
                 </div>
        @elseif ( $picture->allow_comments == 0)
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


@endsection
