@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="">{{ __('Unread ') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">{{ __('All') }}</a>
            </li>
        </ul>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <div class="col-sm-9 col-xs-12 chat" style="overflow: hidden; outline: none;" tabindex="5001">
                    <div class="col-inside-lg decor-default">
                        <div class="chat-body">
                            <form action="{{ route('add.message') }}" method="POST">
                                @csrf
                                <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                                    <input type="text" class="form-control mr-3" name="message" placeholder="{{ __('Add comment...') }}" required>
                                    <input id="invisible_id" name="conversation" type="hidden" value="{{ $conversation }}">
                                    <input id="invisible_id" name="from" type="hidden" value="{{ auth()->user()->id }}">
                                    <button class="btn btn-outline-primary" style="height: 38px;" type="submit"><i class="fas fa-paper-plane"></i></button></div>
                            </form>
                            @foreach($messages as $message)
                                @if ($message->from_id == \Illuminate\Support\Facades\Auth::id())
                                    <div class="answer right">
                                        <div class="avatar">
                                            <img src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $message->from])->pluck('avatar')->first() }}" alt="User name">
                                            <div class="status offline"></div>
                                        </div>
                                        <div class="name">{{ $message->from }}</div>
                                        <div class="text">
                                            {{ $message->message }}
                                        </div>
                                        <div class="time">5 min ago</div>
                                    </div>
                                @else
                                    <div class="answer left">
                                        <div class="avatar">
                                            <img src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $message->from])->pluck('avatar')->first() }}" alt="User name">
                                            <div class="status offline"></div>
                                        </div>
                                        <div class="name">{{ $message->from }}</div>
                                        <div class="text">
                                            {{ $message->message }}
                                        </div>
                                        <div class="time">5 min ago</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
            </div>
                <div class="pagination justify-content-center">
                    {{ $messages->links() }}
                </div>
        </section>
@endsection
@section('javascript')

@endsection
