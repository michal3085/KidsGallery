@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <h2>{{ __('Messages') }}
                    @if ($unfollowing_msg == 1)
                <button class="btn btn-outline-primary shadow fromFollowing" style="float: right; border-radius: 50px;"><i class="fas fa-users" style="height: 30px; width: 30px;"></i></button>
                        </h2>
                    @elseif($unfollowing_msg == 0)
                    <button class="btn btn-outline-primary shadow fromFollowing" style="float: right; border-radius: 50px;"><i class="fas fa-user-lock" style="height: 30px; width: 30px;"></i></button>
                    </h2>
                @endif
                            <br>
                <form action="{{ route('search.messages') }}" method="GET">
                    <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                        <input type="text" class="form-control mr-3" name="search" id="search" placeholder="{{ __('Search users') }}/{{ __('conversations') }}..." required>
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button></div>
                </form>
                @foreach($unread as $new)
                    @if (\App\Models\Message::where('conversation_id', $new->id)->where('to_id', auth()->id())->where('read', 0)->count())
                        <div class="d-flex flex-row comment-row" style="background-color: rgba(66,232,11,0.13); border-radius: 25px;">
                            @else
                                <div class="d-flex flex-row comment-row">
                                    @endif
                                    @if ($new->user_a == \Illuminate\Support\Facades\Auth::user()->name)
                                        <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $new->user_b])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                                        <div class="comment-text w-100">
                                            <a href="{{ route('messages.show', ['id' => $new->id, 'to' => $new->user_b]) }}"><b>{{ $new->user_b }}</b></a>
                                            @else
                                                <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $new->user_a])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                                                <div class="comment-text w-100">
                                                    <a href="{{ route('messages.show', ['id' => $new->id, 'to' => $new->user_a]) }}"><b>{{ $new->user_a }}</b></a>
                                                    @endif
                                                    <div class="comment-footer"> <span class="date">{{ \App\Models\Message::where('conversation_id', $new->id)->where('to_id', \Illuminate\Support\Facades\Auth::id())->where('read', 0)->count() }} / {{ \App\Models\Message::where('conversation_id', $new->id)->count() }}
                                                    </div>
                                                    <p class="m-b-5 m-t-10"></p>
                                                </div>
                                                <button type="button" class="btn btn-link unwanted" data-id="{{ $new->id  }}"><i class="fas fa-comment-slash" style="height: 30px; width: 30px; color: rgba(128,123,125,0.63)"></i></button>
                                        </div>
                                        <hr>
                                        @endforeach
                @foreach($conversations as $conversation)
                    @if (\App\Models\Message::where('conversation_id', $conversation->id)->where('to_id', auth()->id())->where('read', 0)->count())
                        <div class="d-flex flex-row comment-row" style="background-color: rgba(66,232,11,0.13); border-radius: 25px;">
                        @else
                                <div class="d-flex flex-row comment-row">
                                    @endif
                                @if ($conversation->user_a == \Illuminate\Support\Facades\Auth::user()->name)
                                <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $conversation->user_b])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                                <div class="comment-text w-100">
                                <a href="{{ route('messages.show', ['id' => $conversation->id, 'to' => $conversation->user_b]) }}"><b>{{ $conversation->user_b }}</b></a>
                            @else
                                <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $conversation->user_a])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                                <div class="comment-text w-100">
                                <a href="{{ route('messages.show', ['id' => $conversation->id, 'to' => $conversation->user_a]) }}"><b>{{ $conversation->user_a }}</b></a>
                            @endif
                            <div class="comment-footer"> <span class="date">{{ \App\Models\Message::where('conversation_id', $conversation->id)->where('to_id', \Illuminate\Support\Facades\Auth::id())->where('read', 0)->count() }} / {{ \App\Models\Message::where('conversation_id', $conversation->id)->count() }}
                            </div>
                            <p class="m-b-5 m-t-10"></p>
                        </div>
                           <button type="button" class="btn btn-link unwanted" data-id="{{ $conversation->id  }}"><i class="fas fa-comment-slash" style="height: 30px; width: 30px; color: rgba(128,123,125,0.63)"></i></button>
                    </div>
                 <hr>
                @endforeach
            </div>
        </section>
        @endsection
        @section('javascript')
            $( function()  {
            $('.unwanted').click( function () {
            Swal.fire({
            title: '{{ __('You definitely want to hide that conversation?') }}',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '{{ __('Yes') }}',
            cancelButtonText: '{{ __('No') }}'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "POST",
            url: "/message/unwanted/conversation/" + $(this).data("id")
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

            $(function() {
            $('.fromFollowing').click( function () {
            $.ajax({
            method: "POST",
            url: "/messages/recive/switcher"
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
