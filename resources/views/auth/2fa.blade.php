@extends('frontend.layouts.main')

@section('title')
    {{ __('Two-factor authentication') }}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-primary">
                    <div class="card-header bg-primary">{{ __('Two-factor authentication') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('frontend.login.2fa') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('One-time password') }}</label>
                                <div class="col-md-6">
                                    <input id="totp" type="text" class="form-control{{ $errors->has('totp') ? ' is-invalid' : '' }}" name="totp" required>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Verify') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection