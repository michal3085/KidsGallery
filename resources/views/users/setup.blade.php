@extends('layout.index')

@section('content')

    <div class="container-fluid p-0">
        <section class="resume-section" id="about">
            <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                @method('PUT')
                <h2>{{ $user->name }} - {{ __('Settings') }}</h2>
                <br><br>
                <h5>{{ __('Choose Your default avatar') }}:</h5>
                <div class="row section-box">
                    <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 1]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar.png') }}" alt="" /></a>
                        <a href="{{ route('set.avatar', ['id' => $user->id, 'x' => 2]) }}"> <img class="rounded float-left" style="width: 200px; height: 200px;" src="{{ asset('/storage/avatar/avatar2.png') }}" alt="" /></a>
                    </div>
                </div>

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
            </form>
            <hr>
        </section>
    </div>

@endsection
