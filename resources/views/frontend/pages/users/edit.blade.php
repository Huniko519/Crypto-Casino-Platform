@extends('frontend.layouts.main')

@section('title')
    {{ __('Profile') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('frontend.users.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" {{ $user->profiles->isEmpty() ? 'required' : 'readonly' }}>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            {{ __('Save') }}
        </button>
    </form>
@endsection
