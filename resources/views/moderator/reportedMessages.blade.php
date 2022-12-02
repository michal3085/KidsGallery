@extends('moderator.app')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <h2 class="mb-0">
                    {{ __('Reported Messages')  }}
                </h2>
                <hr>
                <div class="col-sm-12 col-xs-12 chat" style="overflow: hidden; outline: none;" tabindex="5001">
                    <div class="col-inside-lg decor-default">
                        <div class="chat-body">
                           @foreach($messages as $message)
                                    <div class="answer left">
                                        <hr>
                                        <div class="avatar">
                                            <a href="{{ route('profiles.about', ['name' => $message->from_name ]) }}">
                                                <img src="{{ asset('/storage') . '/' . \App\Models\User::where(['id' => $message->from_id])->pluck('avatar')->first() }}" alt="User name">
                                            </a>
                                            <div class="status offline"></div>
                                        </div>
                                        <a href="{{ route('profiles.about', ['name' => $message->from_name ]) }}"><div class="name">{{ $message->from_name }}</div></a>
                                        <div class="text">
                                            {{ $message->message }}
                                        </div>
                                        <div class="name">{{ $message->created_at }} <button type="button" class="btn btn-link delete" data-id="{{ $message->id }}"><i class="far fa-trash-alt " style="height: 20px; width: 20px; color: red" data-id="{{ $message->message_id }}"></i></button>
                                            | <button type="button" class="btn btn-link accept" data-id="{{ $message->id }}"><i class="far fa-check-circle " style="height: 20px; width: 20px; color:green"></i></button>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="pagination justify-content-center">
                    {{ $messages->links() }}
                </div>
        </section>
        @endsection
        @section('javascript')
            $( function()  {
            $('.delete').click( function () {
            Swal.fire({
            title: '{{ __('You definitely want to delete this message') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes') }}',
            cancelButtonText: '{{ __('No') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "DELETE",
            url: "/messages/delete/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })
            .fail(function( response ) {
            Swal.fire('Ups', '{{ __('Something went wrong') }}', 'error');
            });
            }
            })
            });
            });

            $( function()  {
            $('.report').click( function () {
            Swal.fire({
            title: '{{ __('Are you sure you want to report this message?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes') }}',
            cancelButtonText: '{{ __('No') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "GET",
            url: "/message/report/" + $(this).data("id")
            })
            .done(function( response ) {
            Swal.fire({
            title: '{{ __('Message has been reported') }}',
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
            $('.accept').click( function () {
            Swal.fire({
            title: '{{ __('You want to accept that message') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes') }}',
            cancelButtonText: '{{ __('No') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "DELETE",
            url: "/moderator/reported/message/accept/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })
            .fail(function( response ) {
            Swal.fire('Ups', '{{ __('Something went wrong') }}', 'error');
            });
            }
            })
            });
            });


            $(function () {
            $('[data-toggle="tooltip"]').tooltip()

            })
@endsection
