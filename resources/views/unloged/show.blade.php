@extends('unloged.index')

@section('content')

    @if ($pictures->message == 1)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Przekazano do moderacji</strong>
        </div>
    @endif

    <section class="resume-section" id="about">
        <div class="resume-section-content">
                <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $pictures->user])->pluck('avatar')->first() }}" alt="user" style="width: 40px; height: 40px;"><b>{{ $pictures->user }}: </b></span></div>
                    <b class="mb-0" style="font-size: 40px;">
                        {{ $pictures->name }}
                            </b>
                            @if ($place_likes == 1 && $place_views == 1)
                                <i class="fas fa-crown" style="font-size: 30px; color: #ffbb2c" data-toggle="tooltip" data-html="true" data-title="Ta praca jest na pierwszym miejscu w TOP10 - Polubien i Wyświetleń!"  data-delay='{"show":"500", "hide":"300"}'></i>
                                    @else
                                @if ($top == 1)
                                    <i class="fas fa-award" style="color: #cea807; font-size: 25px;" data-toggle="tooltip" data-html="true" data-title="Ta praca jest w <b>TOP10 ({{ $place_likes }})</b> pod względem <b>polubień</b>"  data-delay='{"show":"500", "hide":"300"}'></i>
                                            @endif
                                        @if ($top_views == 1)
                                    <i class="fas fa-medal" style="color: #cea807; font-size: 25px;" data-toggle="tooltip" data-html="true" data-title="Ta praca jest w <b>TOP10 ({{ $place_views }})</b> pod względem <b>wyswietleń</b>"  data-delay='{"show":"500", "hide":"300"}'></i>
                                @endif
                            @endif
                                <p class="lead mb-5">
                                    <div class="row section-box">
                                        <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                                            <img src="{{ asset('/storage') . '/' . $pictures->file_path }}" class="img-thumbnail">
                                    <br>
                                    <i class="fas fa-calendar-week" style="color: green"></i>: {{ $pictures->created_at }}
                                    | <i class="far fa-comment-alt" style="color: orange"></i> {{ \App\Models\Comment::where('picture_id', $pictures->id)->count() }}
                                    | <i class="far fa-eye" style="color: #3737ec"></i> {{ $pictures->views }}
                                            <br>
                                            <i class="fas fa-star" style="color: #f3db00"></i> {{ \App\Models\Favorite::where('picture_id', $pictures->id)->count() }}
                                                <form action="{{ route('like.new', ['id' => $pictures->id]) }}" method="post">
                                                    @csrf
                                                <button type="submit" class="btn btn-success px-3" style="float: left"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $pictures->likes()->where('picture_id', $pictures->id)->count() }}</button>
                                            </form>
                                    <a href="{{ route('pictures.report', ['id' => $pictures->id]) }}">
                                        <button type="submit" class="btn btn-outline-danger float-right">{{ __('Report') }}</button>
                                    </a>
                                </div>
                         </div>
                    </p>
                <br>
            <hr>
                {{ $pictures->comment }}
                    <br>
                <hr>
             @if ($pictures->allow_comments == 1)
            @foreach($comments as $comment)
                <div class="d-flex flex-row comment-row">
                    <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2 shadow rounded" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $comment->user_name])->pluck('avatar')->first() }}" alt="user" style="width: 50px; height: 50px;"></span></div>
                    <div class="comment-text w-100">
                        <b>{{ $comment->user_name }}</b>
                        <div class="comment-footer"> <span class="date" style="font-size: 12px;">{{ $comment->created_at }}</span></div>
                        <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                            <i class="far fa-thumbs-up get_comment_like" data-id="{{ $comment->id }}" style="color: green"></i>
                                {{ \App\Models\CommentsLike::where('comment_id', $comment->id)->where('like', 1)->count() }} |
                            <i class="far fa-thumbs-down get_comment_unlike" data-id="{{ $comment->id }}" style="color: red"></i>
                        {{ \App\Models\CommentsLike::where('comment_id', $comment->id)->where('dislike', 1)->count() }}
                    </div>
                </div>
                <hr>
            @endforeach
            <br>
            <div class="pagination justify-content-center">
                {{ $comments->links() }}
            </div>
        </div>
        @elseif ( $pictures->allow_comments == 0)
            <div class="d-flex flex-row comment-row">
                <div class="comment-text w-100">
                    <h6 class="text-center"><i class="fas fa-comment-slash"></i> {{ __('User has not agreed to comments') }}</h6>
                </div>
            </div>
            @endif

        </div>
    </section>
@endsection
@section('javascript')
$(function () {
$('[data-toggle="tooltip"]').tooltip()
})
@endsection