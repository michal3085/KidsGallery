@extends('layout.index')

@section('content')
    <section class="resume-section" id="about">
        <div class="resume-section-content">
          <h2>{{ $pictures->user }}: </h2>
            <h1 class="mb-0">
                {{ $pictures->name }}
            </h1>
                    <p class="lead mb-5">
                    <div class="row section-box">
                            <div class="col-sm-xl text-center description-text">
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
                            <button type="submit" class="btn btn-outline-danger float-right">Usuń</button>
                        </form>

                        <a href="{{ route('pictures.edit', ['picture' => $pictures->id]) }}">
                            <button type="submit" class="btn btn-outline-warning float-left">Edytuj</button>
                        </a>
                    @else

                    <a href="{{ route('pictures.report', ['id' => $pictures->id]) }}">
                        <button type="submit" class="btn btn-outline-danger float-right">Zgłoś</button>
                    </a>

                    @endif

                    @else

                    @endif


                    </p>
                    <br>
                    <hr>
            {{ $pictures->comment }}
        </div>
    </section>

@endsection
