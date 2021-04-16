@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.info', ['name' => $other_user->name]) }}">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.gallery', ['name' => $other_user->name]) }}">Galeria</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.comments', ['name' => $other_user->name]) }}">Komentarze</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.favorites', ['name' => $other_user->name]) }}">Polubione</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.followers', ['name' => $other_user->name]) }}">Obserwowani</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('profiles.following', ['name' => $other_user->name]) }}">Obserwuja</a>
            </li>
        </ul>

        <section class="resume-section" id="about">
            <div class="container portfolio">
                <div class="row">
                    <div class="col-md-12">
                    </div>
                </div>
                <div class="bio-info">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="bio-image">
                                        <img src="{{ asset('/storage') . '/' . $other_user->avatar }}" alt="image" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bio-content">
                                <h1>Hi there, I'm {{  $other_user->name }}</h1>
                                <h6>{{ $userdata->about }}</h6>
                            </div>
                            <div class="bio-content">
                                <h6>Miasto: {{ $userdata->city }}</h6>
                            </div>
                            <div class="bio-content">
                                <h6>Data urodzenia: {{ $userdata->birthdate }}</h6>
                            </div>
                            @if ($user->id != $other_user->id)
                                @if ($user->followers()->where('follower_id', $other_user->id)->where('user_id', \Illuminate\Support\Facades\Auth::id())->count() == 0)
                                    <button type="submit" class="btn btn-outline-danger follow" data-id="{{ $other_user->id }}"><i class="fas fa-heart"></i> Dodaj do ulubionych</button>
                                @else
                                    <button type="submit" class="btn btn-danger" data-id="{{ $other_user->id }}"><i class="fas fa-heart"></i> Obserwujesz</button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
@section('javascript')
            $(function() {
            $('.follow').click( function () {
            $.ajax({
            method: "POST",
            url: "/followers/add/" + $(this).data("id")
            // data: { name: "John", location: "Boston" }
            })
            .done(function( response ) {
            window.location.reload();
            })
            .fail(function( response ) {
            alert( "Error:0001" );
            });
            });
            });
@endsection
