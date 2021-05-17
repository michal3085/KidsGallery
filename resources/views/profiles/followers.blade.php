@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.about', ['name' => $other_user->name]) }}">{{ __('Info') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.gallery', ['name' => $other_user->name]) }}">{{ __('Gallery') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.comments', ['name' => $other_user->name]) }}">{{ __('Comments') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.favorites', ['name' => $other_user->name]) }}">{{ __('Liked') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.following', ['name' => $other_user->name]) }}">{{ __('Following') }} ({{ \App\Models\Follower::where('user_id', $other_user->id)->count() }})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.followers', ['name' => $other_user->name]) }}">{{ __('Followers') }} ({{ \App\Models\Follower::where('follow_id', $other_user->id)->count() }})</a>
            </li>
            @if ($other_user->id == $user->id)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profiles.info', ['name' => $user->name]) }}">{{ __('Info') }} ({{ \App\Models\ModeratorAction::where('user_id', $user->id)->where('moderator_only', 0)->count() }})</a>
                </li>
            @endif
        </ul>

        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4"></div>
                @foreach($followers as $follow)
                    <div class="d-flex flex-row comment-row">
                        <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $follow->name])->pluck('avatar')->first() }}" alt="user" style="height: 50px; width: 50px;"></span></div>
                        <div class="comment-text w-100">
                            <a href="{{ route('profiles.about', ['name' => $follow->name ]) }}"><h5>{{ $follow->name }}</h5></a>
                            <div class="comment-footer"> <span class="date">
                                <p class="m-b-5 m-t-10"></p>
                            </div>
                        </div>
                        @if (\Illuminate\Support\Facades\Auth::Id() != $follow->id)
                            @if (\App\Models\BlockedUser::where('user_id', $user->id)->where('blocks_user', $follow->id)->count() != 0)
                               <i class="fas fa-user-lock" style="height: 40px; width: 40px; color: rgba(71,68,68,0.62)" data-id="{{ $follow->id }}"></i>
                            @else
                                @if ($user->following()->where('follow_id', $follow->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                    <a href="" class="follow" data-id="{{ $follow->id }}"><i class="far fa-heart " style="height: 40px; width: 40px; color: #c82333" ></i></a>
                                @else
                                    <a href="" class="delete" data-id="{{ $follow->id }}"><i class="fas fa-heart " style="height: 40px; width: 40px; color: #c82333" ></i></a>
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
