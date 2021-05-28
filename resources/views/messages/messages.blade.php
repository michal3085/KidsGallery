@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                <div class="col-sm-12 col-xs-12 chat" style="overflow: hidden; outline: none;" tabindex="5001">
                    <div class="col-inside-lg decor-default">
                        <div class="chat-body">
                            @if ($not_allow == 0)
                            <form action="{{ route('send.message') }}" method="POST">
                                @csrf
                                <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                                    <input type="text" class="form-control mr-3" name="message" autofocus="autofocus" style="border-radius: 25px;" placeholder="{{ __('Add comment...') }}" required>
                                    <input id="invisible_id" name="conversation" type="hidden" value="{{ $conversation }}">
                                    <input id="invisible_id" name="from" type="hidden" value="{{ auth()->user()->id }}">
                                    <button class="btn btn-outline-primary" style="height: 38px; border-radius: 25px;" type="submit"><i class="fas fa-paper-plane"></i></button></div>
                            </form>
                            @else
                                <div class="d-flex flex-row comment-row">
                                    <div class="comment-text w-100">
                                        <h5 class="text-center"><i class="fas fa-comment-slash"></i> {{ __('User has blocked you, you cant send him messages') }}</h5>
                                    </div>
                                </div>
                            @endif
                            @foreach($messages as $message)
                                @if ($message->from_id == \Illuminate\Support\Facades\Auth::id())
                                    <div class="answer right">
                                        <div class="avatar">
                                            <a href="{{ route('profiles.about', ['name' => $message->from ]) }}">
                                                <img src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $message->from])->pluck('avatar')->first() }}" alt="User name">
                                            </a>
                                            <div class="status offline"></div>
                                        </div>
                                        <a href="{{ route('profiles.about', ['name' => $message->from ]) }}"><div class="name">{{ $message->from }}</div></a>
                                        <div class="text">
                                            {{ $message->message }}
                                        </div>
                                        <div class="name"><i class="far fa-trash-alt"></i> {{ $message->created_at }}</div>
                                    </div>
                                @else
                                    <div class="answer left">
                                        <div class="avatar">
                                            <a href="{{ route('profiles.about', ['name' => $message->from ]) }}">
                                                <img src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $message->from])->pluck('avatar')->first() }}" alt="User name">
                                            </a>
                                          <div class="status offline"></div>
                                        </div>
                                        <a href="{{ route('profiles.about', ['name' => $message->from ]) }}"><div class="name">{{ $message->from }}</div></a>
                                        <div class="text">
                                            {{ $message->message }}
                                        </div>
                                        <div class="name">{{ $message->created_at }} <i class="fas fa-exclamation"></i></div>
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
