@extends('backend.layouts.main')

@section('title')
    {{ $user->name }} :: {{ __('Delete') }}
@endsection

@section('content')
    <form  class="ui form" method="POST" action="{{ route('backend.users.destroy', $user) }}">
        @csrf
        {{ method_field('DELETE') }}
        <button type="submit" class="btn btn-danger">
            <i class="far fa-trash-alt"></i>
            {{ __('Delete') }}
        </button>
    </form>
    <div class="mt-3">
        <a href="{{ route('backend.users.edit', $user) }}"><i class="fas fa-long-arrow-alt-left"></i> {{ __('Back to user') }}</a>
    </div>
@endsection