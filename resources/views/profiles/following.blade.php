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
                <a class="nav-link active" href="{{ route('profiles.following', ['name' => $other_user->name]) }}">Obserwowani ({{ \App\Models\Follower::where('user_id', $other_user->id)->count() }})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.followers', ['name' => $other_user->name]) }}">Obserwują ({{ \App\Models\Follower::where('follower_id', $other_user->id)->count() }})</a>
            </li>
        </ul>

        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4"></div>
                @foreach($followers as $follow)
                    <div class="d-flex flex-row comment-row">
                        <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $follow->name])->pluck('avatar')->first() }}" alt="user" width="50"></span></div>
                        <div class="comment-text w-100">
                            <a href="{{ route('profiles.info', ['name' => $follow->name ]) }}"><h5>{{ $follow->name }}</h5></a>
                            <div class="comment-footer"> <span class="date">
                                <p class="m-b-5 m-t-10"></p>
                            </div>
                        </div>
                        @if (\Illuminate\Support\Facades\Auth::Id() != $follow->id)
                            @if ($user->name == $other_user->name)
                                @if ($user->following()->where('follower_id', $follow->id)->where('rights', 1)->count() != 0 )
                                <button type="button" class="btn btn-link"><i class="far fa-eye" style="height: 30px; width: 30px;"></i></button>
                                    @elseif ($user->following()->where('follower_id', $follow->id)->where('rights', 1)->count() == 0)
                                        <button type="button" class="btn btn-link"><i class="far fa-eye-slash" style="height: 30px; width: 30px;"></i></button>
                                @endif
                                    <button type="button" class="btn btn-link"><i class="fas fa-heart delete" style="height: 40px; width: 40px; color: #c82333" data-id="{{ $follow->id }}"></i></button>
                            @elseif ($user->name != $other_user->name)
                                @if ($user->following()->where('follower_id', $follow->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                    <button type="button" class="btn btn-link"><i class="far fa-heart follow" style="height: 40px; width: 40px; color: #c82333" data-id="{{ $follow->id }}"></i></button>
                                @else
                                    <button type="button" class="btn btn-link"><i class="fas fa-heart delete" style="height: 40px; width: 40px; color: #c82333" data-id="{{ $follow->id }}"></i></button>
                                @endif
                            @endif
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
