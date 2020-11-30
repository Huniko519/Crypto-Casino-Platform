@extends('frontend.layouts.main')

@section('title')
    {{ __('Chat') }}
@endsection

@section('content')
    <chat :user="{{ json_encode(['id' => auth()->user()->id, 'name' => auth()->user()->name]) }}"></chat>
@endsection