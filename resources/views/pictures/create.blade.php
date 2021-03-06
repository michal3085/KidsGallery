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
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Krótki opis</label>
            <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
        </div>
        Wybierz dostępność twojej pracy dla innych:
        <select name="visible" class="custom-select" id="inputGroupSelect01">
                <option value="1">Publiczny</option>
                <option value="0">Prywatny</option>
        </select>
        <br>
        <br>
        <button type="submit" class="btn btn-success btn-lg">Opublikuj</button>
    </form>
        </section>
    </div>
@endsection