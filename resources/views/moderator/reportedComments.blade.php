@extends('moderator.app')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <h2 class="mb-0">
                    {{ __('Reported Comments')  }}
                </h2>
                <hr>
                <div class="d-flex flex-row add-comment-section mt-4 mb-4"></div>
                @foreach($comments as $comment)
                            <div class="d-flex flex-row comment-row">
                                <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $comment->user_name])->pluck('avatar')->first() }}" alt="user" style="height: 50px; width: 50px;"></span></div>
                                <div class="comment-text w-100">
                                    <h5>{{ $comment->user_name }}</h5>
                                    <div class="comment-footer"> <span class="date">{{ $comment->created_at }}
                                        <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                                    </div>
                                    </span><span class="action-icons"><i class="far fa-trash-alt comment_delete" data-id="{{ $comment->id }}" style="height: 20px; width: 20px; color: red"></i> </span>
                                    | </span><span class="action-icons"><i class="fas fa-clipboard-check report_delete" data-id="{{ $comment->id }}" style="height: 20px; width: 20px; color: green"></i> </span>
                                    | [ {{ __('number of reports') }}:   <b>{{ \App\Models\CommentsReport::where('comment_id', $comment->id)->count() }}</b> ]
                                </div>
                                <button type="button" class="btn btn-outline-success">
                                    <a href="{{ route('moderator.picture', ['id' => $comment->picture_id]) }}">
                                        <img class="img-fluid img-responsive  mr-2" src="{{ asset('/storage') . '/' . \App\Models\Picture::where(['id' => $comment->picture_id])->pluck('file_path')->first() }}" alt="user" style="height: 50px; width: 50px;">
                                    </a>
                                </button>
                            </div>
                            <hr>
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
            $('.report_delete').click( function () {
            Swal.fire({
            title: '{{ __('You definitely want to delete this report?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes, delete that report') }}',
            cancelButtonText: '{{ __('No, do not delete') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "DELETE",
            url: "/moderator/delete/comment/report/" + $(this).data("id")
            })
            .done(function( response ) {
            Swal.fire({
            title: '{{ __('Report has been removed') }}',
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
