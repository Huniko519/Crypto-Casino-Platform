@extends('frontend.layouts.main')

@section('title')
    {{ __('Top losses') }}
@endsection

@section('content')
    @if($games->isEmpty())
        <div class="alert alert-info" role="alert">
            {{ __('There are no games yet.') }}
        </div>
    @else
        <table class="table table-hover table-stackable">
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Game') }}</th>
                <th class="text-right">
                    <i class="fas fa-arrow-down"></i>
                    {{ __('Bet') }}
                </th>
                <th class="text-right">{{ __('Win') }}</th>
                <th class="text-right">{{ __('Played') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($games as $game)
                <tr>
                    <td data-title="{{ __('Name') }}">
                        <a href="{{ route('frontend.users.show', $game->account->user) }}">
                            {{ $game->account->user->name }}
                        </a>
                    </td>
                    <td data-title="{{ __('Game') }}">
                        {{ $game->title }}
                    </td>
                    <td data-title="{{ __('Bet') }}" class="text-right">
                        {{ $game->_bet }}
                    </td>
                    <td data-title="{{ __('Win') }}" class="text-right">
                        {{ $game->_win }}
                    </td>
                    <td data-title="{{ __('Played') }}" class="text-right">
                        {{ $game->updated_at->diffForHumans() }}
                        <span data-balloon="{{ $game->updated_at }}" data-balloon-pos="up">
                            <i class="far fa-clock" ></i>
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection