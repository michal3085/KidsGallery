@extends('layout.index')

@section('content')
    @foreach($pictures as $picture)
        <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
    @endforeach
@endsection