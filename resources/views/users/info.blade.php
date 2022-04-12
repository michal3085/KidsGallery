@extends('layout.index')

@section('content')
    <div class="container-fluid p-0">
        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users.edit', ['user' => $user->id]) }}">Avatar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('user.info', ['id' => $user->id]) }}">Informacje</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.account') }}">Konto</a>
            </li>
        </ul>
        <div class="container-fluid p-0">
            <section class="resume-section" id="about">
                <form action="{{ route('user.aboutsave', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <h2>{{ __('About You') }}:</h2>
                    <br><br>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">{{ __('Short Description') }}:<small> ({{ __('Field not required') }})</small></label>
                        <textarea class="form-control" style="width: 600px; height: 250px;" id="about" name="about" rows="3">{{ $userdata->about }}</textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="city" id="city" value="{{ $userdata->city }}" placeholder="{{ __('City') }}">
                    </div>
                    {{ __('Birthdate') }}:
                    <div class="form-group">
                        <input type="date" class="form-control" name="birthdate" id="birthdate" value="{{ $userdata->birthdate }}">
                    </div>
                    <br>
                    <br>
                    <button type="submit" class="btn btn-success btn-lg">{{ __('Save') }}</button>
                </form>
    </div>

@endsection
