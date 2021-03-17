@extends('layout.index')

@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('message') }}</strong>
        </div>
    @endif

    <section class="resume-section" id="about">
        <div class="resume-section-content">
          <h2>{{ $pictures->user }}: </h2>
            <h1 class="mb-0">
                {{ $pictures->name }}
            </h1>
                    <p class="lead mb-5">
                    <div class="row section-box">
                            <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                                    <img src="{{ asset('/storage') . '/' . $pictures->file_path }}" class="img-thumbnail">
                                <br>
                                Dodane: {{ $pictures->created_at }}
                                | Edytowane : {{ $pictures->updated_at }}
                            </div>
                    </div>
                    <form action="{{ route('pictures.newlike', ['id' => $pictures->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success px-3"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $pictures->likes }}</button>
                    </form>
                    <br>

                    @if (Auth::check())
                        @if (Auth::user()->name == $pictures->user)
                            <form method="POST" action="{{ route('pictures.destroy', ['picture' => $pictures->id]) }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-outline-danger float-right" onclick="return confirm('Na pewno chcesz usunąć swoja prace?')">Usuń</button>
                            </form>

                            <a href="{{ route('pictures.edit', ['picture' => $pictures->id]) }}">
                                <button type="submit" class="btn btn-outline-success float-left">Edytuj</button>
                            </a>
                        @else

                        <a href="{{ route('pictures.report', ['id' => $pictures->id]) }}">
                            <button type="submit" class="btn btn-outline-danger float-right">Zgłoś</button>
                        </a>
                        @endif
                    @endif
                    </p>
                <br>
            <hr>
            {{ $pictures->comment }}
        </div>
    </section>
@endsection
