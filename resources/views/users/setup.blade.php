@extends('layout.index')

@section('content')

    <div class="container-fluid p-0">

        <br>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="">Avatar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.info', ['id' => $user->id]) }}">Informacje</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="">Konto</a>
            </li>
        </ul>

        <section class="resume-section" id="about">
            <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                @method('PUT')
                <b style="font-size: 25px;">{{ $user->name }} - {{ __('Settings') }}</b>
                <br><br>
                <h5>{{ __('Choose Your avatar') }}:</h5>
                <div class="row section-box">
                    <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                        <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage') . '/' . $user->avatar }}" alt="" />
                    </div>
                </div>
                <div class="form-group">
                    <input type="file" class="form-control-file" name="avatar" id="avatar">
                </div>
                    <br>
                        <br>
                            <button type="submit" class="btn btn-success btn-lg">{{ __('Add') }}</button>
                        <hr>
                    <br><br>
                <h5>{{ __('Choose Your default avatar') }}:</h5>
                    <div class="row section-box">
                    <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                        <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
                        <hr>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 1]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar1.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 2]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar2.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 3]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar3.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 4]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar4.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 5]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar5.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 6]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar6.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 7]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar7.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 8]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar8.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 9]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar9.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 10]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar10.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 11]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar11.png') }}" alt="" /></a>
                    </div>
                </div>
            </form>
            <hr>
        </section>
    </div>

@endsection
