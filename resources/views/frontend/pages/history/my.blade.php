@extends('frontend.layouts.main')

@section('title')
    {{ __('My games') }}
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
                <th>{{ __('ID') }}</th>
                <th>{{ __('Game') }}</th>
                <th class="text-right">{{ __('Bet') }}</th>
                <th class="text-right">{{ __('Win') }}</th>
                <th>{{ __('Result') }}</th>
                <th class="text-right">
                    <i class="fas fa-arrow-down"></i>
                    {{ __('Played') }}
                </th>
                <th>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach ($games as $game)
                <tr>
                    <td data-title="{{ __('ID') }}">
                        {{ $game->id }}
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
                    <td data-title="{{ __('Result') }}">
                        {{ $game->gameable->result }}
                    </td>
                    <td data-title="{{ __('Played') }}" class="text-right">
                        {{ $game->updated_at->diffForHumans() }}
                        <span data-balloon="{{ $game->updated_at }}" data-balloon-pos="up">
                            <i class="far fa-clock" ></i>
                        </span>
                    </td>
                    <td class="text-right">
                        <a href="{{ route('frontend.history.verify', $game) }}" class="btn btn-primary btn-sm">{{ ('Verify') }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $games->links() }}
        </div>
    @endif
@endsection