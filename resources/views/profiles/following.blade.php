@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.info', ['name' => $other_user->name]) }}">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Galeria</a>
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
                <a class="nav-link active" href="{{ route('profiles.following', ['name' => $other_user->name]) }}">Obserwują</a>
            </li>
        </ul>

        <main role="main">

            <div class="album py-5 bg-light">
                <div class="container">
                    @foreach($followers as $follower)
                        {{ $follower->name }} <br>
                    @endforeach
                </div>
            </div>
    </div>

    </main>

@endsection
