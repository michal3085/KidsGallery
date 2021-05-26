@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('messages.list') }}">{{ __('All') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('unread.messages') }}">{{ __('Unread ') }} ({{ auth()->user()->newMessages()->count() }})</a>
            </li>
        </ul>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <form action="{{ route('search.messages') }}" method="GET">
                    <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                        <input type="text" class="form-control mr-3" name="search" id="search" placeholder="{{ __('Search users') }}..." required>
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button></div>
                </form>
                @foreach($users as $user)
                    @if ($user->name != \Illuminate\Support\Facades\Auth::user()->name)
                    <div class="d-flex flex-row comment-row">
                        <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $user->name])->pluck('avatar')->first() }}" alt="user" style="height: 50px; width: 50px;"></span></div>
                        <div class="comment-text w-100">
                            <a href="{{ route('messages.show', ['to' => $user->name]) }}"><h5>{{ $user->name }}</h5></a>
                            <div class="comment-footer"> <span class="date">
                                <p class="m-b-5 m-t-10"></p>
                            </div>
                        </div>
                        </div>
                        <hr>
                        @endif
                @endforeach
                </div>
            </div>
        </section>
@endsection
@section('javascript')

@endsection
