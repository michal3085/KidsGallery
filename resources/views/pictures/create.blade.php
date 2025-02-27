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
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid p-0">
        <section class="resume-section" id="about">
    <form action="{{ route('pictures.store') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <h2>{{ __('Insert new image') }}:</h2>
        <br><br>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('Picture name') }}">
                </div>
                    <div class="form-group">
                        <input type="file" class="form-control-file" name="file" id="file">
                    </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">{{ __('Short Description') }}:<small> ({{ __('Field not required') }})</small></label>
                            <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                        </div>
                            {{ __('Choose to make your work accessible to others') }}:
                            <select name="visible" class="custom-select" id="inputGroupSelect01">
                                    <option value="1">{{ __('Public') }}</option>
                                    <option value="0">{{ __('Private') }}</option>
                            </select>
                            <br>
                            {{ __('Allow comments') }}:
                        <select name="allowcomments" class="custom-select" id="inputGroupSelect01">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                        <br>
            <br>
            <button type="submit" class="btn btn-success btn-lg">{{ __('Add') }}</button>
        </form>
        </section>
    </div>
@endsection