@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="#">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.index', ['name' => $other_user[0]->name]) }}">Galeria</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.comments', ['name' => $user->name]) }}">Komentarze</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Polubione</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Obserwowani</a>
            </li>
        </ul>

        <section class="resume-section" id="about">
            <div class="resume-section-content">
                    <div class="d-flex flex-row add-comment-section mt-4 mb-4"></div>
                @foreach($comments as $comment)
                    <div class="d-flex flex-row comment-row">
                        <div class="p-2"><span class="round"><img class="img-fluid img-responsive rounded-circle mr-2" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $comment->user_name])->pluck('avatar')->first() }}" alt="user" width="50"></span></div>
                        <div class="comment-text w-100">
                            <h5>{{ $comment->user_name }}</h5>
                            <div class="comment-footer"> <span class="date">{{ $comment->created_at }}
                                    @if ( $comment->user_name == \Illuminate\Support\Facades\Auth::user()->name)
                                        </span><span class="action-icons"><i class="far fa-trash-alt comment_delete" data-id="{{ $comment->id }}"></i> </span>
                                @else
                                </span><span class="action-icons"><i class="fas fa-exclamation comment_report" data-id="{{ $comment->id }}"></i></span>
                                @endif
                                <p class="m-b-5 m-t-10">{{ $comment->comment }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-success">
                            <a href="{{ route('pictures.show', ['picture' => $comment->picture_id]) }}">
                            <img class="img-fluid img-responsive  mr-2" src="{{ asset('/storage') . '/' . \App\Models\Picture::where(['id' => $comment->picture_id])->pluck('file_path')->first() }}" alt="user" width="50">
                            </a>
                        </button>
                    </div>
                    <hr>
                @endforeach
            <div class="pagination justify-content-center">
                {{ $comments->links() }}
            </div>
        </div>
    </section>


@endsection
