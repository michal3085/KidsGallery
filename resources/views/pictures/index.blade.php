@extends('layout.index')

@section('content')
    @foreach($pictures as $picture)
        <div class="container">
            <div class="row section-box">
                <div class="col-sm-4 text-center description-text">
                    <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                    <b>{{ $picture->user }}</b> | {{ $picture->name }}
                </div>
            </div>
        </div>


    @endforeach
@endsection