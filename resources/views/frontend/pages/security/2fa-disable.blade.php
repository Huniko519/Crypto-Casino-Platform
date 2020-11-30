@extends('frontend.layouts.main')

@section('title')
    {{ __('Disable two-factor authentication') }}
@endsection

@section('content')
    <p>
        {{ __('It is not recommended to disable two-factor authentication.') }}
        {{ __('If you still wish to proceed please input 2FA one-time password and your current account password below.') }}
    </p>
    <form method="POST" action="{{ route('frontend.security.2fa.disable') }}">
        @csrf
        <div class="form-group">
            <label>{{ __('One-time password') }}</label>
            <input type="text" name="totp" class="form-control" required autofocus>
            <small>{{ __('Input one-time password that you currently see in the Google Authenticator app.') }}</small>
        </div>
        <div class="form-group">
            <label>{{ __('Account password') }}</label>
            <input type="password" name="password" class="form-control" required autofocus>
            <small>{{ __('Input the password that you use to log in to the website.') }}</small>
        </div>
        <button type="submit" class="btn btn-danger">{{ __('Disable 2FA') }}</button>
    </form>
@endsection