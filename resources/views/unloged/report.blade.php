@extends('unloged.index')

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
    <form action="{{ route('pictures.report', ['id' => $pictures->id]) }}" method="POST">
        {{ csrf_field() }}
        @method('PUT')
        <h2>Zgłoszenie pracy "{{ $pictures->name }}":</h2>
        <br><br>
             <div class="form-group">
                 <label for="exampleFormControlTextarea1">Powód zgłoszenia:<small> (pole niewymagane)</small></label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
             </div>

            <br>
            <button type="submit" class="btn btn-success btn-lg">Zgłoś</button>
        </form>
        </section>
    </div>
@endsection
