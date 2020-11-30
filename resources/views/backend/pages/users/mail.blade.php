@extends('backend.layouts.main')

@section('title')
    {{ $user->name }} :: {{ __('Mail') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('backend.users.mail.send', array_merge(['user' => $user], request()->query())) }}">
        @csrf

        <div class="form-group">
            <label>{{ __('Email') }}</label>
            <input type="text" class="form-control" value="{{ $user->email }}" readonly>
        </div>

        <div class="form-group">
            <label>{{ __('Subject') }}</label>
            <input type="text" name="subject" class="form-control {{ $errors->has('subject') ? 'is-invalid' : '' }}" placeholder="{{ __('Subject') }}" value="{{ old('subject') }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('Greeting') }}</label>
            <input type="text" name="greeting" class="form-control {{ $errors->has('greeting') ? 'is-invalid' : '' }}" value="{{ old('greeting', __('Hello')) }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('Message') }}</label>
            <textarea name="message" class="form-control" rows="10" required>{{ old('message') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-envelope"></i>
            {{ __('Send') }}
        </button>
    </form>
    <div class="mt-3">
        <a href="{{ route('backend.users.index', request()->query()) }}"><i class="fas fa-long-arrow-alt-left"></i> {{ __('Back to all users') }}</a>
    </div>
@endsection
