@extends('backend.layouts.main')

@section('title')
    {{ __('Game') }} {{ $game->id }} :: {{ __('View') }}
@endsection

@section('content')
    <div class="form-group">
        <label>{{ __('User') }}</label>
        <input class="form-control text-muted" value="{{ $game->account->user->name }}" readonly>
    </div>
    <div class="form-group">
        <label>{{ __('Bet') }}</label>
        <input class="form-control text-muted" value="{{ $game->bet }}" readonly>
    </div>
    <div class="form-group">
        <label>{{ __('Win') }}</label>
        <input class="form-control text-muted" value="{{ $game->win }}" readonly>
    </div>
    <div class="form-group">
        <label>{{ __('Secret') }}</label>
        <input class="form-control text-muted" value="{{ $game->secret }}" readonly>
    </div>
    <div class="form-group">
        <label>{{ __('Server seed') }}</label>
        <input class="form-control text-muted" value="{{ $game->server_seed }}" readonly>
    </div>
    <div class="form-group">
        <label>{{ __('Client seed') }}</label>
        <input class="form-control text-muted" value="{{ $game->client_seed }}" readonly>
    </div>

    @include($game_package_id . '::backend.pages.games.show')

    <div class="form-group">
        <label>{{ __('Created at') }}</label>
        <input class="form-control text-muted" value="{{ $game->created_at }} ({{ $game->created_at->diffForHumans() }})" readonly>
    </div>
    <div class="form-group">
        <label>{{ __('Updated at') }}</label>
        <input class="form-control text-muted" value="{{ $game->updated_at }} ({{ $game->updated_at->diffForHumans() }})" readonly>
    </div>

    <div class="mt-3">
        <a href="{{ route('backend.games.index', request()->query()) }}"><i class="fas fa-long-arrow-alt-left"></i> {{ __('Back to all games') }}</a>
    </div>
@endsection