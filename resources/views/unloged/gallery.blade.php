@extends('unloged.index')

@section('content')
    <section class="resume-section" id="about">
        <div class="resume-section-content">
            <h1 class="mb-0">
                {{ __('Main Gallery') }}
            </h1>
            @foreach($pictures as $picture)
                @if (  $picture->visible == 1 && $picture->accept == 1  )
                    <p class="lead mb-5">
                    <div class="row section-box">
                        <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                            <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                            </a>
                            <br>
                            <b>{{ $picture->user }}</b> | {{ $picture->name }}
                            <br>
                            {{ __('Added.') }}: {{ $picture->created_at }}
                        </div>
                    </div>
                    <form action="{{ route('like.new', ['id' => $picture->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success px-3"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                    </form>
                    </p>
                @endif
            @endforeach
            <div class="pagination justify-content-center">
                {{ $pictures->links() }}
            </div>
        </div>
    </section>

@endsection
