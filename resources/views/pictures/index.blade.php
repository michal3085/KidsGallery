@extends('layout.index')

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
    <section class="resume-section" id="about">
        <div class="resume-section-content">
            <form action="{{ route('picture.search') }}" method="GET">
                <div class="d-flex flex-row add-comment-section mt-4 mb-4">
                    <input type="text" class="form-control mr-3" @isset($search) value="{{ $search }}" @endisset name="search" id="search" placeholder="{{ __('Search') }}..." required>
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button></div>
            </form>

            @foreach($pictures as $picture)
                        <p class="lead mb-5">
                        <div class="row section-box">
                    @if ($picture->visible == 0)
                        <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                            <i class="fas fa-unlock" style="font-size: 25px;"></i>
                            @else
                                <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                                    @endif
                                <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                    <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                                </a>
                                <br>
                                <a href="{{ route('profiles.about', ['name' => $picture->user ]) }}">
                                    <img class="img-fluid img-responsive rounded-circle mr-1" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $picture->user])->pluck('avatar')->first() }}" alt="user" style="width: 30px; height: 30px;">
                                    <b>{{ $picture->user }}</b></a> | <b>{{ $picture->name }}</b>
                                <br>
                                <i class="fas fa-calendar-week"></i>: {{ $picture->created_at }}
                                | <i class="far fa-comment-alt"></i> {{ \App\Models\Comment::where('picture_id', $picture->id)->count() }}
                                | <i class="far fa-eye"></i> {{ $picture->views }}
                            </div>
                        </div>
                        @if ($picture->likes()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                            <button type="submit" class="btn btn-outline-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                        @else
                            <button type="submit" class="btn btn-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                        @endif
            @endforeach
            <div class="pagination justify-content-center">
                {{ $pictures->links() }}
            </div>
        </div>
    </section>

@endsection
@section('javascript')
    $(function() {
    $('.like').click( function () {
    $.ajax({
    method: "POST",
    url: "/newlike/" + $(this).data("id")
    // data: { name: "John", location: "Boston" }
    })
    .done(function( response ) {
    window.location.reload();
    })
    .fail(function( response ) {
    alert( "Juz polubione" );
    });
    });
    });

@endsection
