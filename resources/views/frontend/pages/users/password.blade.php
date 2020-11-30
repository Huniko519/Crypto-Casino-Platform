@extends('frontend.layouts.main')

@section('title')
    {{ __('Change password') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('frontend.users.password.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>{{ __('Current password') }}</label>
            <input type="password" name="current_password" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}" placeholder="{{ __('Current password') }}" value="{{ old('current_password') }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('New password') }}</label>
            <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="{{ __('Password') }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('Repeat password') }}</label>
            <input type="password" name="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" placeholder="{{ __('Repeat password') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            {{ __('Save') }}
        </button>
    </form>
@endsection