@extends('moderator.app')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4"></div>
                @foreach($reports as $reported)

                        <div class="d-flex flex-row comment-row">
                            <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['id' => $reported->user_id])->pluck('avatar')->first() }}" alt="user" width="50"></span></div>
                            <div class="comment-text w-100">
                                <h5>{{ $reported->user_name }}</h5>
                                <div class="comment-footer"> <span class="date">{{ $reported->created_at }}

                                    <p class="m-b-5 m-t-10">{{ $reported->reason }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-link"><i class="far fa-thumbs-down report_down" style="height: 40px; width: 40px; color: #fdd705" data-id="{{ $reported->id }}"></i></button>
                            @if (\App\Models\PicturesReport::where('picture_id', $reported->picture_id)->count() > 1)
                                <button type="button" class="btn btn-link"><i class="fas fa-check-double report_del_all" style="height: 40px; width: 40px; color: #2e8d19" data-id="{{$reported->picture_id}}"></i></button>
                                <a href="{{ route('reported.pictures', ['id' => $reported->picture_id]) }}">
                                    <button type="button" class="btn btn-link"><i class="fas fa-list-ol report_show_all" style="height: 40px; width: 40px; color: #2e8d19" data-id="{{$reported->picture_id}}"></i></button>
                                </a>
                            @endif
                            @if (\App\Models\Picture::where('id', $reported->picture_id)->where('accept', 1)->count() == 1)
                                <button type="button" class="btn btn-link"><i class="far fa-eye ban_picture" style="height: 40px; width: 40px; color: #2e8d19" data-id="{{ $reported->picture_id }}"></i></button>
                                @else
                                    <button type="button" class="btn btn-link"><i class="far fa-eye-slash unban_picture" style="height: 40px; width: 40px; color: #fd081c" data-id="{{ $reported->picture_id }}"></i></button>
                            @endif
                            <button type="button" class="btn btn-outline-success">
                                <a href="{{ route('pictures.show', ['picture' => $reported->picture_id]) }}">
                                    <img class="img-fluid img-responsive  mr-2" src="{{ asset('/storage') . '/' . \App\Models\Picture::where(['id' => $reported->picture_id])->pluck('file_path')->first() }}" alt="user" width="50">
                                </a>
                            </button>
                        </div>
                        <hr>

                @endforeach
                <div class="pagination justify-content-center">
                    {{ $reports->links() }}
                </div>
            </div>
        </section>


        @endsection
        @section('javascript')
            $( function()  {
            $('.ban_picture').click( function () {
            Swal.fire({
            title: '{{ __('You definitely want to ban that picture?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes') }}',
            cancelButtonText: '{{ __('No') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "POST",
            url: "/moderator/picture/block/" + $(this).data("id")
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
            $('.unban_picture').click( function () {
            Swal.fire({
            title: '{{ __('You definitely want to unban that picture?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes') }}',
            cancelButtonText: '{{ __('No') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "POST",
            url: "/moderator/picture/unblock/" + $(this).data("id")
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
            $('.report_down').click( function () {
            Swal.fire({
            title: '{{ __('Are you sure to delete this report?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes') }}',
            cancelButtonText: '{{ __('No') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "DELETE",
            url: "/report/down/" + $(this).data("id")
            })
            .done(function( response ) {
            Swal.fire({
            title: '{{ __('Report has been deleted') }}',
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

            $( function()  {
            $('.report_del_all').click( function () {
            Swal.fire({
            title: '{{ __('Are you sure to delete this report?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes') }}',
            cancelButtonText: '{{ __('No') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "DELETE",
            url: "/report/down/all/" + $(this).data("id")
            })
            .done(function( response ) {
            Swal.fire({
            title: '{{ __('Report has been deleted') }}',
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
