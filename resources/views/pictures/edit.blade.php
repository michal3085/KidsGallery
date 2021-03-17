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
    <form action="{{ route('pictures.update', ['picture' => $pictures->id]) }}" method="POST">
        {{ csrf_field() }}
        @method('PUT')
        <h2>Edycja pracy {{ $pictures->name }}:</h2>
        <br><br>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" value="{{ $pictures->name }}">
                </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Krótki opis:<small> (pole niewymagane)</small></label>
                            <textarea class="form-control" id="comment" name="comment" rows="3">{{ $pictures->comment }}</textarea>
                        </div>
                            Wybierz dostępność twojej pracy dla innych:
                            <select name="visible" class="custom-select" id="inputGroupSelect01">
                                @if ($pictures->visible == 1)
                                    <option value="1" selected>Publiczna</option>
                                    <option value="0">Prywatna</option>
                                @else
                                    <option value="1">Publiczna</option>
                                    <option value="0" selected>Prywatna</option>
                                @endif
                            </select>
                            <br>
                        Wybierz album do którego chcesz dodać obraz:
                        <select name="album" class="custom-select" id="inputGroupSelect01">
                            <option value="1">Główny</option>
                            <option value="0">...</option>
                        </select>
                        <br>
            <br>
            <button type="submit" class="btn btn-success btn-lg">Zapisz zmiany</button>
        </form>
        </section>
    </div>
@endsection
