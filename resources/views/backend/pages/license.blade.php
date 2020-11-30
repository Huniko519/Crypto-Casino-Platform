@extends('backend.layouts.main')

@section('title')
    {{ __('License registration') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('backend.license.register') }}">
        @csrf
        @if(!env('PURCHASE_CODE') || !env('LICENSEE_EMAIL'))
            <div class="alert alert-warning" role="alert">
                {{ __('Please register your license to continue using the application.') }}
            </div>
        @endif
        <div class="form-group">
            <label>{{ __('Purchase code') }}</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', env('PURCHASE_CODE')) }}" required>
        </div>
        <div class="form-group">
            <label>{{ __('License holder (licensee) email') }}</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', env('LICENSEE_EMAIL')) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
    </form>
@endsection