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
    <form action="{{ route('pictures.update', ['picture' => $pictures->id]) }}" method="POST">
        {{ csrf_field() }}
        @method('PUT')
        <h2>{{ __('Edit image') }} "{{ $pictures->name }}":</h2>
        <br><br>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" value="{{ $pictures->name }}">
                </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">{{ __('Short Description') }}:<small> ({{ __('Field not required') }})</small></label>
                            <textarea class="form-control" id="comment" name="comment" rows="10">{{ $pictures->comment }}</textarea>
                        </div>
                            {{ __('Choose to make your work accessible to others') }}:
                            <select name="visible" class="custom-select" id="inputGroupSelect01">
                                @if ($pictures->visible == 1)
                                    <option value="1" selected>{{ __('Public') }}</option>
                                    <option value="0">{{ __('Private') }}</option>
                                @else
                                    <option value="1">{{ __('Public') }}</option>
                                    <option value="0" selected>{{ __('Private') }}</option>
                                @endif
                            </select>
                            <br>
                        {{ __('Allow comments') }}:
                        <select name="allowcomments" class="custom-select" id="inputGroupSelect01">
                            @if ($pictures->allow_comments == 1)
                                <option value="1" selected>{{ __('Yes') }}</option>
                                <option value="0">{{ __('No') }}</option>
                            @else
                                <option value="1">{{ __('Yes') }}</option>
                                <option value="0" selected>{{ __('No') }}</option>
                            @endif

                        </select>
                        <br>
            <br>
            <button type="submit" class="btn btn-success btn-lg">{{ __('Save changes') }}</button>
        </form>
        </section>
    </div>
@endsection
