@extends('moderator.app')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link @if( $open == 1 ) active @endif"href="{{ route('moderator.actions', ['open' => 1]) }}"><i class="far fa-user"></i> {{ __('Open') }}({{ auth()->user()->countModeratorActions() }})</a>
            </li>
            <li class="nav-item">
                <a  class="nav-link @if( $open == 0 ) active @endif" href="{{ route('moderator.actions', ['open' => 0]) }}"><i class="fas fa-user-check"></i> {{ __('Close') }}({{ auth()->user()->countClosedModeratorActions() }})</a>
            </li>
            @if ( $admin == 1)
            <li class="nav-item">
                <a  class="nav-link "><i class="fas fa-user-slash"></i> {{ __('Blocked') }}</a>
            </li>
            <li class="nav-item">
                <a  class="nav-link "><i class="fas fa-users-cog"></i> {{ __('Moderators') }}</a>
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
                    @foreach($new_actions as $action)
                        <tr>
                            <th scope="row">{{ $action->id }}</th>
                            <td>{{ $action->action }}</td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 250px;">
                                    {{ $action->reason }}
                                </span>
                            </td>
                            @if ($action->type == "picture")
                                <td><a href="{{ route('moderator.details', ['id' => $action->id]) }}">Details</a></td>
                            @endif
                            @if ($action->type == "close_pic" )
                                <td><a href="{{ route('moderator.picture', ['id' => $action->type_id]) }}">Show Picture</a></td>
                            @endif
                            @if ($action->moderator_viewed == 0 )
                                <td><p style="color: green"><b>YES</b></p></td>
                            @else
                                <td>NO</td>
                            @endif
                        </tr>
                    @endforeach
                    @foreach($actions as $action)
                        <tr>
                            <th scope="row">{{ $action->id }}</th>
                            <td>{{ $action->action }}</td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 250px;">
                                    {{ $action->reason }}
                                </span>
                            </td>
                            @if ($action->type == "picture" )
                                <td><a href="{{ route('moderator.details', ['id' => $action->id]) }}">Picture Details</a></td>
                            @endif
                            @if ($action->type == "close_pic" )
                                <td><a href="{{ route('moderator.picture', ['id' => $action->type_id]) }}">Show Picture</a></td>
                            @endif
                            @if ($action->type == "comment" )
                                <td><a href="{{ route('moderator.details', ['id' => $action->id]) }}">Comment Details</a></td>
                            @endif
                            @if ($action->moderator_viewed == 0 )
                                <td><p style="color: green"><b>YES</b></p></td>
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



@endsection
