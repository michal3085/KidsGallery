@extends('unloged.index')

@section('content')
    <section class="resume-section" id="about">
        <div class="resume-section-content">
            <h1 class="mb-0">
                {{ __('Main Gallery') }}
            </h1>
            <form action="{{ route('picture.search') }}" method="GET">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                    <input type="text" class="form-control mr-3" @isset($search) value="{{ $search }}" @endisset name="search" id="search" placeholder="{{ __('Search') }}..." required>
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button></div>
            </form>
                @foreach($pictures as $picture)
                    @if (  $picture->visible == 1 && $picture->accept == 1  )
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
            <div class="pagination justify-content-center">
                {{ $pictures->links() }}
            </div>
        </div>
    </section>

@endsection
