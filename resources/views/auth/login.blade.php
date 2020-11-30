@extends('frontend.layouts.main')

@section('title')
    {{ __('Login') }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary">
                <div class="card-header bg-primary">{{ __('Login') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        @if(config('settings.recaptcha.public_key'))
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="g-recaptcha" data-sitekey="{{ config('settings.recaptcha.public_key') }}" data-theme="{{ config('settings.theme')=='light-blue' ? 'light' : 'dark' }}"></div>
                                </div>
                            </div>
                        @endif

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        @social
                            <div class="row mt-3">
                                <div class="col text-center">
                                    <div class="or-seperator"><i>{{ __('or') }}</i></div>
                                    <span>{{ __('Log in with') }}</span>
                                    <div class="btn-group ml-2">
                                        @foreach(array_keys(config('services.login_providers')) as $provider)
                                            @social($provider)
                                                <a href="{{ url('login/' . $provider) }}" class="btn btn-primary">
                                                    <i class="{{ config('services.login_providers.' . $provider . '.icon') }} mr-1"></i>
                                                    {{ ucfirst($provider) }}
                                                </a>
                                                @break
                                            @endsocial
                                        @endforeach
                                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu">
                                            @foreach(array_keys(config('services.login_providers')) as $provider)
                                                @social($provider)
                                                    <a href="{{ url('login/' . $provider) }}" class="dropdown-item">
                                                        <i class="{{ config('services.login_providers.' . $provider . '.icon') }}"></i>
                                                        {{ ucfirst($provider) }}
                                                    </a>
                                                @endsocial
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endsocial
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if(config('settings.recaptcha.public_key'))
    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endpush
@endif
