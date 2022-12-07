@extends('moderator.app')

@section('content')
    <div class="container-fluid p-0">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session()->get('message') }}</strong>
            </div>
        @endif
        <section class="resume-section" id="about">
            <div class="resume-section-content">

                <div class="row section-box">

                    <div class="col-sm-xl text-center description-text shadow p-3 mb-5 rounded">
                        <img src="{{ asset('/storage') . '/' . $picture->file_path }}" class="img-thumbnail">
                        <br>
                        {{ $user_name }} |
                        <i class="fas fa-calendar-week"></i>: {{ $picture->created_at }}
                        | <i class="far fa-eye"></i> {{ $picture->views }}
                    </div>
                </div>
                <h5><p class="text-center">{{__('Reason')}}:</p></h5>
                <form action="{{ route('update.reason', ['id' => $info->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" id="reason" name="reason" rows="5">{{ $info->reason }}</textarea>
                    </div>
                    <input type="hidden" id="info" value="{{$info->id}}" name="info">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Save') }}</button>
                </form>
                <hr>
                @if ($info->user_response != NULL)
                    <h5><p class="text-center">{{__('User Answer')}}:</p></h5>
                    <div class="row">
                        {{ $info->user_response }}
                    </div>
                    <hr>
                @endif
                <h5><p class="text-center">{{__('Yours Answer')}}:</p></h5>
                <form action="{{ route('moderator.answer', ['id' => $info->id]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control" id="user_answer" name="user_answer" rows="5">{{ $info->moderator_response }}</textarea>
                    </div>
                    <input type="hidden" id="info" value="{{$info->id}}" name="info">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('Send answer') }}</button>
                </form>
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

            $(function() {
            $('.delete').click( function () {
            $.ajax({
            method: "DELETE",
            contentType: "application/json; charset=utf-8",
            url: "/followers/delete/" + $(this).data("id")
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

            $(function() {
            $('.rightson').click( function () {
            $.ajax({
            method: "POST",
            url: "/followers/add/rights/" + $(this).data("id")
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

            $(function() {
            $('.rightsdel').click( function () {
            $.ajax({
            method: "DELETE",
            contentType: "application/json; charset=utf-8",
            url: "/followers/delete/rights/" + $(this).data("id")
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
