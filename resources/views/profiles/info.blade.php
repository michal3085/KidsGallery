@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('profiles.info', ['name' => $other_user[0]->name]) }}">Info</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.gallery', ['name' => $other_user[0]->name]) }}">Galeria</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profiles.comments', ['name' => $other_user[0]->name]) }}">Komentarze</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Polubione</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Obserwowani</a>
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
                                        <img src="{{ asset('/storage') . '/' . $other_user[0]->avatar }}" alt="image" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bio-content">
                                <h1>Hi there, I'm {{  $other_user[0]->name }}</h1>
                                <h6>I am a fresh web designer and I create custom web designs. I'm skilled at writing well-designed, testable and efficient code using current best practices in Web development. I'm a fast learner, hard worker and team player who is proficient in making creative and innovative web pages.</h6>
                                <p>P.S I have no special talent, I'm just passionately curious ;)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
