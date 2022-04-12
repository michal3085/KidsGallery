@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.edit', ['user' => $user->id]) }}">Avatar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.info', ['id' => $user->id]) }}">Informacje</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('user.account') }}">Konto</a>
            </li>
        </ul>
        <div class="container-fluid p-0">
            <section class="resume-section" id="about">
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-warning" type="submit" name="logout">{{ __('Logout') }}</button>
                </form>
        </div>

@endsection
