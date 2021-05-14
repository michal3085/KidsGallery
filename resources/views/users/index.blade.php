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
    <div class="container-fluid">
        <div class="px-lg-5">
            <!-- For demo purpose -->
            <div class="row py-5">
                <div class="col-lg-12 mx-auto">
                    <div class="text-white p-5 shadow-sm rounded banner">
                        <h1 class="display-4">{{ __('Yours Gallery') }}</h1>
                    </div>
                </div>
            </div>
            <!-- End -->
            <div class="row">
            @foreach($pictures as $picture)
                <!-- Gallery item -->
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                        <div class="bg-white rounded shadow p-1 mb-5 rounded">
                            <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}">
                                <img src="{{ asset('/storage') . '/' . $picture->file_path }}" alt="" class="img-fluid card-img-top">
                            </a>
                            <div class="p-4">
                                <h5> <a href="{{ route('pictures.show', ['picture' => $picture->id]) }}" class="text-dark">{{ $picture->name }}</a></h5>
                                    <p class="small text-muted mb-0">
                                        <a href="{{ route('profiles.about', ['name' => $picture->user ]) }}"><img class="img-fluid img-responsive rounded-circle mr-2" src="{{ asset('/storage') . '/' . \App\Models\User::where(['name' => $picture->user])->pluck('avatar')->first() }}" alt="user" style="width: 25px; height: 25px;">
                                            {{ $picture->user }}</a>
                                    </p>
                                <hr>
                                @if ($picture->likes()->where('picture_id', $picture->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                    <button type="submit" class="btn btn-outline-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                                @else
                                    <button type="submit" class="btn btn-success px-3 like" data-id="{{ $picture->id }}"><i class="far fa-thumbs-up" aria-hidden="true"></i>  {{ $picture->likes()->where('picture_id', $picture->id)->count() }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- End -->
                @endforeach

            </div>
            <div class="pagination justify-content-center">
                {{ $pictures->links() }}
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    $(function() {
    $('.like').click( function () {
    $.ajax({
    method: "POST",
    url: "/newlike/" + $(this).data("id")
    // data: { name: "John", location: "Boston" }
    })
    .done(function( response ) {
    window.location.reload();
    })
    .fail(function( response ) {
    alert( "Juz polubione" );
    });
    });
    });

@endsection
