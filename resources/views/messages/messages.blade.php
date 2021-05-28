@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <div class="col-sm-12 col-xs-12 chat" style="overflow: hidden; outline: none;" tabindex="5001">
                    <div class="col-inside-lg decor-default">
                        <div class="chat-body">
                            @if ($not_allow == 0)
                            <form action="{{ route('send.message') }}" method="POST">
                                @csrf
                                <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                                    <input type="text" class="form-control mr-3" name="message" autofocus="autofocus" style="border-radius: 25px;" placeholder="{{ __('Add comment...') }}" required>
                                    <input id="invisible_id" name="conversation" type="hidden" value="{{ $conversation }}">
                                    <input id="invisible_id" name="from" type="hidden" value="{{ auth()->user()->id }}">
                                    <button class="btn btn-outline-primary" style="height: 38px; border-radius: 25px;" type="submit"><i class="fas fa-paper-plane"></i></button></div>
                            </form>
                            @else
                                <div class="d-flex flex-row comment-row">
                                    <div class="comment-text w-100">
                                        <h5 class="text-center"><i class="fas fa-comment-slash"></i> {{ __('User has blocked you, you cant send him messages') }}</h5>
                                    </div>
                                </div>
                            @endif
                            @foreach($messages as $message)
                                @if ($message->from_id == \Illuminate\Support\Facades\Auth::id())
                                    <div class="answer right">
                                        <div class="avatar">
                                            <a href="{{ route('profiles.about', ['name' => $message->from ]) }}">
                                                <img src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $message->from])->pluck('avatar')->first() }}" alt="User name">
                                            </a>
                                            <div class="status offline"></div>
                                        </div>
                                        <a href="{{ route('profiles.about', ['name' => $message->from ]) }}"><div class="name">{{ $message->from }}</div></a>
                                        <div class="text">
                                            {{ $message->message }}
                                        </div>
                                        <div class="name"><i class="far fa-trash-alt delete" data-id="{{ $message->id }}"></i> {{ $message->created_at }}
                                            @if ($message->read == 1)
                                                <i class="far fa-eye" data-toggle="tooltip" data-html="true" data-title="Wiadomość wyświetlona przez <b>{{ $message->to }}</b>: <br> {{ $message->updated_at }}"  data-delay='{"show":"500", "hide":"300"}'></i>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="answer left">
                                        <div class="avatar">
                                            <a href="{{ route('profiles.about', ['name' => $message->from ]) }}">
                                                <img src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $message->from])->pluck('avatar')->first() }}" alt="User name">
                                            </a>
                                          <div class="status offline"></div>
                                        </div>
                                        <a href="{{ route('profiles.about', ['name' => $message->from ]) }}"><div class="name">{{ $message->from }}</div></a>
                                        <div class="text">
                                            {{ $message->message }}
                                        </div>
                                        <div class="name">{{ $message->created_at }} <i class="fas fa-exclamation report" data-id="{{ $message->id }}"></i></div>
                                    </div>
                                @endif
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


            $(function () {
            $('[data-toggle="tooltip"]').tooltip()

            })
@endsection
