@extends('backend.layouts.main')

@section('title')
    {{ $package->name }} :: {{ ('Install or update') }}
@endsection

@section('content')
    <form  class="ui form" method="POST" action="{{ route('backend.addons.install.run', $package->id) }}">
        @csrf
        <div class="form-group">
            <label>{{ __('Purchase code') }}</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', env(str_replace('-', '_', $package->id) . '_PURCHASE_CODE')) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">
            {{ __('Proceed') }}
        </button>
    </form>
    <div class="mt-3">
        <a href="{{ route('backend.addons.index') }}"><i class="fas fa-long-arrow-alt-left"></i> {{ __('Back to all add-ons') }}</a>
    </div>
@endsection
