@extends('layout.index')

@section('content')
    <section class="resume-section" id="about">
        <div class="resume-section-content">
            <h1 class="mb-0">
                Galeria Główna
            </h1>
            @foreach($pictures as $picture)
                @if (  $picture->visible == 1  )
                    <p class="lead mb-5">
                        <div class="row section-box">
                            <div class="col-sm-xl text-center description-text">
                                <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                    <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                                </a>
                                    <br>
                                    <b>{{ $picture->user }}</b> | {{ $picture->name }}
                                    <br>
                                Dodane: {{ $picture->created_at }}
                            </div>

                        </div>
                    <form action="{{ route('pictures.newlike', ['id' => $picture->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success px-3"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes }}</button>
                    </form>
                    </p>
                    @endif
                    @endforeach
        </div>
    </section>

@endsection
