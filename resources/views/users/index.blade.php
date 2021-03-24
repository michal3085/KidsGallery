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

    <section class="resume-section" id="about">
        <div class="resume-section-content">
            <h1 class="mb-0">
                {{ \Illuminate\Support\Facades\Auth::user()->name }}
            </h1>
            @foreach($pictures as $picture)
                @if (  $picture->visible == 1 )
                    <p class="lead mb-5">
                    <div class="row section-box">
                        <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                            <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                            </a>
                            <br>
                            <b>{{ $picture->user }}</b> | {{ $picture->name }}
                            <br>
                            Dodane: {{ $picture->created_at }}
                        </div>
                    </div>
                    @if ($picture->likes()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                        <button type="submit" class="btn btn-outline-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                    @else
                        <button type="submit" class="btn btn-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                    @endif
                @endif
            @endforeach
            {{--            <div class="pagination pagination-lg justify-content-center">--}}
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
                // .fail(function( response ) {
                // alert( "Juz polubione" );
                // });
            });
    });

@endsection
