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
        <h2>WSTAW NOWE DZIEŁO:</h2>
        <br><br>
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
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Publiczny</label>
        </div>
        <br>
        <button type="submit" class="btn btn-success btn-lg">Wyślij</button>
    </form>
        </section>
    </div>
@endsection