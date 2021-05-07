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
                <a class="nav-link" href="{{ route('profiles.followers', ['name' => $other_user->name]) }}">{{ __('Followers') }} ({{ \App\Models\Follower::where('follow_id', $other_user->id)->count() }})</a>
            </li>
            @if ($other_user->id == $user->id)
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('profiles.info', ['name' => $other_user->name]) }}">{{ __('Info') }} ({{ \App\Models\ModeratorAction::where('user_id', $user->id)->where('moderator_only', 0)->count() }})</a>
                </li>
            @endif
        </ul>

        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Type') }}</th>
                        <th scope="col">{{ __('Reason') }}</th>
                        <th scope="col">{{ __('More details and options') }}</th>
                        <th scope="col">{{ __('Answer') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($actions as $action)
                        <tr>
                            <th scope="row">{{ $action->id }}</th>
                            <td>{{ $action->action }}</td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 250px;">
                                    {{ $action->reason }}
                                </span>
                            </td>
                            @if ($action->type == "picture")
                                <td><a href="{{ route('banned.picture', ['picture_id' => $action->type_id, 'mod_info' => $action->id, 'name' => $other_user->name]) }}">Details</a></td>
                            @endif
                            @if ($action->moderator_response == 1)
                                <td>YES</td>
                            @else
                                <td>NO</td>
                            @endif
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                <div class="pagination justify-content-center">
                    {{ $actions->links() }}
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

            $(function() {
            $('.rightson').click( function () {
            $.ajax({
            method: "POST",
            url: "/followers/add/rights/" + $(this).data("id")
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
            $('.rightsdel').click( function () {
            $.ajax({
            method: "DELETE",
            contentType: "application/json; charset=utf-8",
            url: "/followers/delete/rights/" + $(this).data("id")
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
