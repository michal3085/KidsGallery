@extends('unloged.index')

@section('content')
    @include('terms.' . (session()->get('locale') ?? 'pl') . '_pp')
@endsection
