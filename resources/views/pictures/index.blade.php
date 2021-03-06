@extends('layout.index')

@section('content')
    @foreach($pictures as $picture)
        @if (  $picture->visible == 1  )
        <div class="album py-5 bg-light">
            <div class="row section-box">
                <div class="col-sm-xl text-center description-text">
                    <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                    <br>
                    <b>{{ $picture->user }}</b> | {{ $picture->name }}
                    <br>
                    Dodane: {{ $picture->created_at }}
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endsection