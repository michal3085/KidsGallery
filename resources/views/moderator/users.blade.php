@extends('moderator.app')

@section('content')
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4"></div>
                @foreach($users as $user)
                    <div class="d-flex flex-row comment-row">
                        <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $user->name])->pluck('avatar')->first() }}" alt="user" style="height: 50px; width: 50px;"></span></div>
                        <div class="comment-text w-100">
                            <br>
                            <a href="{{ route('profiles.about', ['name' => $user->name ]) }}"><b>{{ $user->name }}</b></a>
                            <div class="comment-footer"> <span class="date">
                                <p class="m-b-5 m-t-10"></p>
                            </div>
                        </div>
                                @if ($user->active == 1)
                                    <a href="" class="block" data-id="{{ $user->id }}"><i class="fas fa-user-check " style="height: 40px; width: 40px; color: #39c823" ></i></a>
                                @else
                                    <a href="" class="unblock" data-id="{{ $user->id }}"><i class="fas fa-user-slash " style="height: 40px; width: 40px; color: #c82333" ></i></a>
                                @endif
                    </div>
                    <hr>
                @endforeach
                <div class="pagination justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </section>

        @endsection
        @section('javascript')
            $(function() {
            $('.block').click( function () {
            $.ajax({
            method: "POST",
            url: "/moderator/user/block/" + $(this).data("id")
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
            $('.unblock').click( function () {
            $.ajax({
            method: "POST",
            contentType: "application/json; charset=utf-8",
            url: "/moderator/user/unblock/" + $(this).data("id")
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
