@extends('moderator.app')

@section('content')
    <div class="container-fluid p-0">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('message') }}</strong>
            </div>
        @endif
        <section class="resume-section" id="about">
            <div class="resume-section-content">
                @if ( $type == 'picture')
                    <div class="row section-box">
                        <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                            <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                            <br>
                            <i class="fas fa-calendar-week"></i>: {{ $picture->created_at }}
                            | <i class="far fa-eye"></i> {{ $picture->views }}
                        </div>
                    </div>
                @endif
                    @if ( $type == 'comment')
                        <h3>Comment:</h3>
                        <div class="d-flex flex-row comment-row">
                            <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $comment->user_name])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                            <div class="comment-text w-100">
                                <a href="{{ route('profiles.about', ['name' => $comment->user_name ]) }}"><b></b>{{ $comment->user_name }}</b></a>
                                <div class="comment-footer"> <span class="date" style="font-size: 12px;">{{ $comment->created_at }}
                                </div>
                                <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                                @if (\App\Models\CommentsLike::where('comment_id', $comment->id)->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->where('like', 1)->count() == 1)
                                    <i class="fas fa-thumbs-up get_comment_like" data-id="{{ $comment->id }}" style="color: green"></i>
                                @else
                                    <i class="far fa-thumbs-up get_comment_like" data-id="{{ $comment->id }}" style="color: green"></i>
                                @endif
                                {{ \App\Models\CommentsLike::where('comment_id', $comment->id)->where('like', 1)->count() }} |
                                @if (\App\Models\CommentsLike::where('comment_id', $comment->id)->where('user_id', \Illuminate\Support\Facades\Auth::user()->id)->where('dislike', 1)->count() == 1)
                                    <i class="fas fa-thumbs-down get_comment_unlike" data-id="{{ $comment->id }}" style="color: red"></i>
                                @else
                                    <i class="far fa-thumbs-down get_comment_unlike" data-id="{{ $comment->id }}" style="color: red"></i>
                                @endif
                                {{ \App\Models\CommentsLike::where('comment_id', $comment->id)->where('dislike', 1)->count() }}
                            </div>
                            <button type="button" class="btn btn-outline-success">
                                <a href="{{ route('pictures.show', ['picture' => $comment->picture_id]) }}">
                                    <img class="img-fluid img-responsive  mr-2" src="{{ asset('/storage') . '/' . \App\Models\Picture::where(['id' => $comment->picture_id])->pluck('file_path')->first() }}" alt="user" style="height: 50px; width: 50px;">
                                </a>
                            </button>
                        </div>
                        <hr>
                    @endif
                <br>
                    User name: <a href="{{ route('profiles.about', ['name' => $user_name ]) }}"><b>{{ $user_name }}</a></b><br>
                    Action type: <b>{{ $action->action }}</b><br>
                        @if ( $type == 'picture')
                            Picture ID: <b>{{  $picture->id}} </b><br>
                            Picture add from IP: <b>{{ $picture->ip }}</b>
                        @endif
                            @if ( $type == 'comment')
                                Comment ID: <b>{{ $comment->id }} </b><br>
                                Comment add from IP: <b>?</b><br>
                            @endif
                        <h5><p class="text-center">{{__('Reason')}}:</p></h5>
                            <form action="{{ route('update.reason', ['id' => $info->id]) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <textarea class="form-control" id="reason" name="reason" rows="5">{{ $info->reason }}</textarea>
                                </div>
                                <input type="hidden" id="info" value="{{$info->id}}" name="info">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Save') }}</button>
                            </form>
                        <hr>
                    @if ($info->user_response != NULL)
                        <h5><p class="text-center">{{__('User Answer')}}:</p></h5>
                            <div class="row">
                                {{ $info->user_response }}
                            </div>
                        <hr>
                @endif
                <h5><p class="text-center">{{__('Yours Answer')}}:</p></h5>
                <form action="{{ route('moderator.answer', ['id' => $info->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" id="answer" name="answer" rows="5">{{ $info->moderator_response }}</textarea>
                    </div>
                    <input type="hidden" id="info" value="{{$info->id}}" name="info">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Send answer') }}</button>
                </form>
            </div>
        </section>

        @endsection
        @section('javascript')
@endsection
