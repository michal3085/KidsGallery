@extends('unloged.index')

@section('content')

    @if ($pictures->message == 1)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Przekazano do moderacji</strong>
        </div>
    @endif

    <section class="resume-section" id="about">
        <div class="resume-section-content">
          <h2>{{ $pictures->user }}: </h2>
            <h1 class="mb-0">
                {{ $pictures->name }}
            </h1>
                    <p class="lead mb-5">
                    <div class="row section-box">
                            <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                                    <img src="{{ asset('/storage') . '/' . $pictures->file_path }}" class="img-thumbnail">
                                <br>
                                {{ __('Added.') }}: {{ $pictures->created_at }}
                                |  {{ __('Edited') }}: {{ $pictures->updated_at }}
                            </div>
                    </div>
                    <form action="{{ route('like.new', ['id' => $pictures->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success px-3"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $pictures->likes()->where('picture_id', $pictures->id)->count() }}</button>
                    </form>
                    <br>
                        <a href="{{ route('pictures.report', ['id' => $pictures->id]) }}">
                            <button type="submit" class="btn btn-outline-danger float-right">{{ __('Report') }}</button>
                        </a>

                    </p>
                <br>
            <hr>
            {{ $pictures->comment }}
            <br>
            @foreach($comments as $comment)
                <div class="d-flex flex-row comment-row">
                    <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2" src="{{ asset('assets/img/antos.png') }}" alt="user" width="50"></span></div>
                    <div class="comment-text w-100">
                        <h5>{{ $comment->user_name }}</h5>
                        <div class="comment-footer"> <span class="date">{{ $comment->created_at }}</span></div>
                        <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                    </div>
                </div>
            @endforeach
            <br>
            <div class="pagination justify-content-center">
                {{ $comments->links() }}
            </div>
        </div>

        </div>
    </section>
@endsection
