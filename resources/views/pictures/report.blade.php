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
    <form action="{{ route('pictures.sendreport', ['id' => $pictures->id]) }}" method="POST">
        {{ csrf_field() }}
        @method('PUT')
        <h2>{{ __('Image Submission') }} "{{ $pictures->name }}":</h2>
        <br><br>
             <div class="form-group">
                 <label for="exampleFormControlTextarea1">{{ __('Reason for submission') }}:<small> ({{ __('Field not required') }})</small></label>
                <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
             </div>

            <br>
            <button type="submit" class="btn btn-success btn-lg">{{ __('Report') }}</button>
        </form>
        </section>
    </div>
@endsection
