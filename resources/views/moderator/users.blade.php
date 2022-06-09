@extends('moderator.app')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a @if ($mode == 1) class="nav-link active" @else class="nav-link" @endif href="{{ route('moderator.users') }}"><i class="far fa-user"></i> {{ __('All') }}</a>
            </li>
            <li class="nav-item">
                <a  @if ($mode == 2) class="nav-link active" @else class="nav-link" @endif href="{{ route('moderator.activeUsers') }}"><i class="fas fa-user-check"></i> {{ __('Active') }}</a>
            </li>
            <li class="nav-item">
                <a  @if ($mode == 3) class="nav-link active" @else class="nav-link" @endif href="{{ route('moderator.blockedUsers') }}"><i class="fas fa-user-slash"></i> {{ __('Blocked') }}</a>
            </li>
            <li class="nav-item">
                <a  @if ($mode == 4) class="nav-link active" @else class="nav-link" @endif href="{{ route('moderator.list') }}"><i class="fas fa-users-cog"></i> {{ __('Moderators') }}</a>
            </li>
        </ul>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <form action="{{ route('moderator.search.user', ['mode' => $mode]) }}" method="GET">
                    <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                        <input type="text" class="form-control mr-3" name="search" id="search" placeholder="{{ __('Search users') }}..." required>
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button></div>
                    <p>Users found: {{ $users->total() }}</p>
                </form>
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
                            {{--                        Control Buttons--}}
                            @if ($user->active == 1)
                                @if (\App\Models\Role::where('user_id', $user->id)->where('role', 'moderator')->count() == 1 && $user->hasRole('moderator') && !$user->hasRole('admin'))
                                    <button type="button" class="btn btn-link" data-html="true" data-toggle="tooltip" data-title="This user is a moderator, you must have administrator privileges to perform actions on this account." data-delay='{"show":"500", "hide":"300"}'><i class="fas fa-user-astronaut" style="height: 35px; width: 35px; color: rgba(148,145,145,0.47)"></i></button>
                                @else
                                    <button type="button" class="btn btn-link block" data-id="{{ $user->id }}"><i class="fas fa-user-check " style="height: 35px; width: 35px; color: #39c823" ></i></button>
                            @endif
                                    @else
                                <button type="button" class="btn btn-link unblock" data-id="{{ $user->id }}"><i class="fas fa-user-slash " style="height: 35px; width: 35px; color: #c82333;" ></i></button>
                            @endif
                                @if ($user->active == 1 && \App\Models\Role::where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->where('role', 'admin')->count() == 1)
                                    @if (\App\Models\Role::where('user_id', $user->id)->where('role', 'moderator')->count() == 1)
                                        <button type="button" class="btn btn-link be_normal" data-id="{{ $user->id }}"><i class="fas fa-user-cog" style="height: 35px; width: 35px; color: #1d9308;"></i></button>
                                            @else
                                        <button type="button" class="btn btn-link be_moderator" data-id="{{ $user->id }}"><i class="fas fa-user-cog" style="height: 35px; width: 35px; color: rgba(148,145,145,0.57);"></i></button>
                                    @endif
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

            });
            });

            $(function() {
            $('.be_moderator').click( function () {
            $.ajax({
            method: "POST",
            contentType: "application/json; charset=utf-8",
            url: "/moderator/new/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })

            });
            });

            $(function() {
            $('.be_normal').click( function () {
            $.ajax({
            method: "DELETE",
            contentType: "application/json; charset=utf-8",
            url: "/moderator/delete/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })
            // .fail(function( response ) {
            // alert( "Error:0001" );
            // });
            });
            });

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })

{{--            $( function()  {--}}
{{--            $('.be_moderator').click( function () {--}}
{{--            Swal.fire({--}}
{{--            title: '{{ __('You definitely want to delete this comment?') }}',--}}
{{--            icon: 'question',--}}
{{--            showCancelButton: true,--}}
{{--            confirmButtonText: '{{ __('Yes, delete that comment') }}',--}}
{{--            cancelButtonText: '{{ __('No, do not delete') }}'--}}
{{--            }).then((result) => {--}}
{{--            if (result.value) {--}}
{{--            $.ajax({--}}
{{--            method: "POST",--}}
{{--            url: "/moderator/new/moderator/" + $(this).data("id")--}}
{{--            })--}}
{{--            .done(function( response ) {--}}
{{--            Swal.fire({--}}
{{--            title: '{{ __('Comment has been removed') }}',--}}
{{--            icon: 'success',--}}
{{--            showCancelButtonText: true,--}}
{{--            confirmButtonText: 'OK'--}}
{{--            }).then((result) => {--}}
{{--            window.location.reload();--}}
{{--            })--}}

{{--            })--}}
{{--            .fail(function( response ) {--}}
{{--            Swal.fire('Ups', '{{ __('Something went wrong') }}', 'error');--}}
{{--            });--}}
{{--            }--}}
{{--            })--}}
{{--            });--}}
{{--            });--}}
@endsection
