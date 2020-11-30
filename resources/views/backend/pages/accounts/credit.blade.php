@extends('backend.layouts.main')

@section('title')
    {{ __('Credit account') }} :: {{ $account->id }}
@endsection

@section('content')
    <form method="POST" action="{{ route('backend.accounts.credit', $account) }}">
        @csrf
        <div class="form-group">
            <label>{{ __('Amount') }}</label>
            <input name="amount" type="number" class="form-control" placeholder="100" value="{{ old('amount') }}">
        </div>
        <button class="btn btn-primary" type="submit">
            {{ __('Credit') }}
        </button>
    </form>
@endsection