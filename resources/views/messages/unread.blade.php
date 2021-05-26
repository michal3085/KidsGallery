@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('messages.list') }}">{{ __('All') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('unread.messages') }}">{{ __('Unread ') }} ({{ auth()->user()->newMessages()->count() }})</a>
            </li>
        </ul>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <form action="{{ route('search.messages') }}" method="GET">
                    <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                        <input type="text" class="form-control mr-3" name="search" id="search" placeholder="{{ __('Search users') }}..." required>
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button></div>
                </form>
                @foreach($conversations as $conversation)
                    <div class="d-flex flex-row comment-row">
                        @if ($conversation->user_a == \Illuminate\Support\Facades\Auth::user()->name)
                            <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $conversation->user_b])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                            <div class="comment-text w-100">
                                <a href="{{ route('messages.show', ['id' => $conversation->id, 'to' => $conversation->user_b]) }}"><h5>{{ $conversation->user_b }}</h5></a>
                                @else
                                    <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $conversation->user_a])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                                    <div class="comment-text w-100">
                                        <a href="{{ route('messages.show', ['id' => $conversation->id, 'to' => $conversation->user_a]) }}"><h5>{{ $conversation->user_a }}</h5></a>
                                        @endif
                                        <div class="comment-footer"> <span class="date">{{ \App\Models\Message::where('conversation_id', $conversation->id)->where('to_id', \Illuminate\Support\Facades\Auth::id())->where('read', 0)->count() }} / {{ \App\Models\Message::where('conversation_id', $conversation->id)->count() }}
                                        </div>
                                        <p class="m-b-5 m-t-10"></p>
                                    </div>
                            </div>
                            <hr>
                            @endforeach
                    </div>
        </section>
@endsection
@section('javascript')

@endsection
