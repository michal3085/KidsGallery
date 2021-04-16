@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.info', ['name' => $other_user->name]) }}">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.gallery', ['name' => $other_user->name]) }}">Galeria</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.comments', ['name' => $other_user->name]) }}">Komentarze</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.favorites', ['name' => $other_user->name]) }}">Polubione</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.followers', ['name' => $other_user->name]) }}">Obserwowani ({{ \App\Models\Follower::where('user_id', $other_user->id)->count() }})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.following', ['name' => $other_user->name]) }}">Obserwują ({{ \App\Models\Follower::where('follower_id', $other_user->id)->count() }})</a>
            </li>
        </ul>

        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4"></div>
                @foreach($followers as $follow)
                    <div class="d-flex flex-row comment-row">
                        <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $follow->name])->pluck('avatar')->first() }}" alt="user" width="50"></span></div>
                        <div class="comment-text w-100">
                            <a href="{{ route('profiles.info', ['name' => $follow->name ]) }}"><h5>{{ $follow->name }}</h5></a>
                            <div class="comment-footer"> <span class="date">
                                <p class="m-b-5 m-t-10"></p>
                            </div>
                        </div>

                            @if ($user->followers()->where('follower_id', $follow->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                <button type="submit" class="btn btn-outline-danger follow" data-id="{{ $follow->id }}"><i class="fas fa-heart"></i> Dodaj do ulubionych</button>
                            @else
                                <button type="submit" class="btn btn-danger delete" data-id="{{ $follow->id }}"><i class="fas fa-heart"></i> Usuń z ulubionych</button>
                            @endif

                    </div>
                    <hr>
                @endforeach
                <div class="pagination justify-content-center">
                    {{ $followers->links() }}
                </div>
            </div>
        </section>

@endsection
        @section('javascript')
            $(function() {
            $('.follow').click( function () {
            $.ajax({
            method: "POST",
            url: "/followers/add/" + $(this).data("id")
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
            $('.delete').click( function () {
            $.ajax({
            method: "DELETE",
            contentType: "application/json; charset=utf-8",
            url: "/followers/delete/" + $(this).data("id")
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
