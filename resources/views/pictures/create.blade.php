@extends('layout.index')

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message') }}</strong>
        </div>
    @endif
    <div class="container-fluid p-0">
        <section class="resume-section" id="about">
    <form action="{{ route('pictures.store') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="form-group">
            <br>
            <input type="text" class="form-control" name="user" id="user" placeholder="Nazwa użykownika">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="name" id="name" placeholder="Nazwa obrazu">
        </div>
        <div class="form-group">
            <input type="file" class="form-control-file" name="file" id="file">
        </div>
        <br>
        <button type="submit" class="btn btn-success">Wyślij</button>
    </form>
        </section>
    </div>
@endsection