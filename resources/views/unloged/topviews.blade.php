@extends('unloged.index')

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message') }}</strong>
        </div>
    @endif

    @if (session()->has('message2'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message2') }}</strong>
        </div>
    @endif
    <br>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('top10') }}">Polubienia</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('top10.views') }}">Wyświetlenia</a>
        </li>
    </ul>

    <section class="resume-section" id="about">
        <div class="resume-section-content">
            <h1 class="mb-0">
                TOP 10
            </h1>
            @foreach($pictures as $picture)
                @if (  $picture->visible == 1 && $picture->accept == 1 )
                    <p class="lead mb-5">
                    <div class="row section-box">
                        <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                            <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                            </a>
                            <br>
                            <img class="img-fluid img-responsive rounded-circle mr-1" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $picture->user])->pluck('avatar')->first() }}" alt="user" style="width: 30px; height: 30px;">
                            <b>{{ $picture->user }}</b> | {{ $picture->name }}
                            <br>
                            <i class="fas fa-calendar-week" style="color: green"></i>: {{ $picture->created_at }}
                            | <i class="far fa-comment-alt" style="color: orange"></i> {{ \App\Models\Comment::where('picture_id', $picture->id)->count() }}
                            | <i class="far fa-eye" style="color: #3737ec"></i> {{ $picture->views }}
                            <br>
                            <form action="{{ route('like.new', ['id' => $picture->id]) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-success px-3" style="float: left"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                            </form>
                        </div>
                    </div>
                    </p>
                @endif
            @endforeach
        </div>
    </section>

@endsection
