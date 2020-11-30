@extends('backend.layouts.main')

@section('title')
    {{ __('Dashboard') }} :: {{ __('Games') }}
@endsection

@section('content')
    @include('backend.pages.dashboard.tabs')

    <div class="row text-center mt-3">
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Games played') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $games_count }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Played last 30 days') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $played_last_month }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Played last 7 days') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $played_last_week }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center mt-3">        
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Avg bet') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $avg_bet }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Max bet') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $max_bet }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Total bet') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $total_bet }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center mt-3">        
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Avg net win') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $avg_net_win }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Max net win') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $max_net_win }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Total net win') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $total_net_win }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12 mb-4">
            <h2 class="text-center">{{ __('Games played by day') }}</h2>
            <time-series-chart :data="{{ json_encode($played_by_day) }}" :scrollbar="true" theme="{{ $settings->theme }}" class="time-series-chart"></time-series-chart>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12 mb-4">
            <h2 class="text-center">{{ __('Net win by day') }}</h2>
            <time-series-chart :data="{{ json_encode($net_win_by_day) }}" :scrollbar="true" type="column" theme="{{ $settings->theme }}" class="time-series-chart"></time-series-chart>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12 col-lg-6 mb-4">
            <h2 class="text-center">{{ __('Games by result') }}</h2>
            <pie-chart :data="{{ json_encode($played_by_result) }}" theme="{{ $settings->theme }}" class="pie-chart"></pie-chart>
        </div>
        <div class="col-sm-12 col-lg-6 mb-4">
            <h2 class="text-center">{{ __('Games by type') }}</h2>
            <pie-chart :data="{{ json_encode($played_by_type) }}" theme="{{ $settings->theme }}" class="pie-chart"></pie-chart>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12">
            <h2 class="text-center mb-4">{{ __('Extra games stats') }}</h2>
            <table class="table table-hover table-stackable">
                <thead>
                <tr>
                    <th>{{ __('Game') }}</th>
                    <th class="text-right">{{ __('Bet') }}</th>
                    <th class="text-right">{{ __('Win') }}</th>
                    <th class="text-right">{{ __('Net win') }}</th>
                    <th class="text-right">{{ __('Return to player') }}</th>
                    <th class="text-right">{{ __('House edge') }}</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($played_by_type_ext as $item)
                        <tr>
                            <td data-title="{{ __('Game') }}">{{ $item->game }}</td>
                            <td data-title="{{ __('Bet') }}" class="text-right">{{ $item->_bet }}</td>
                            <td data-title="{{ __('Win') }}" class="text-right">{{ $item->_win }}</td>
                            <td data-title="{{ __('Net win') }}" class="text-right">{{ $item->_net_win }}</td>
                            <td data-title="{{ __('Return to player') }}" class="text-right">{{ $item->_return_to_player }}</td>
                            <td data-title="{{ __('House edge') }}" class="text-right">{{ $item->_house_edge }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12 col-lg-6 mb-4">
            <h2 class="text-center">{{ __('Top wins') }}</h2>
            @if($top_wins->isEmpty())
                <div class="alert alert-info" role="alert">
                    {{ __('There are no games yet.') }}
                </div>
            @else
                <table class="table table-hover table-stackable">
                    <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Game') }}</th>
                        <th class="text-right">{{ __('Net win') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($top_wins as $game)
                        <tr>
                            <td data-title="{{ __('User') }}">
                                <a href="{{ route('frontend.users.show', $game->account->user) }}">
                                    {{ $game->account->user->name }}
                                </a>
                            </td>
                            <td data-title="{{ __('Game') }}">
                                <a href="{{ route('backend.games.show', $game) }}">
                                    {{ __('app.game_' . class_basename(str_replace('GameMultiSlots', 'GameSlots', $game->gameable_type))) }}
                                </a>
                            </td>
                            <td data-title="{{ __('Net win') }}" class="text-right">{{ $game->_net_win }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="col-sm-12 col-lg-6 mb-4">
            <h2 class="text-center">{{ __('Top losses') }}</h2>
            @if($top_losses->isEmpty())
                <div class="alert alert-info" role="alert">
                    {{ __('There are no games yet.') }}
                </div>
            @else
                <table class="table table-hover table-stackable">
                    <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Game') }}</th>
                        <th class="text-right">{{ __('Net loss') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($top_losses as $game)
                        <tr>
                            <td data-title="{{ __('User') }}">
                                <a href="{{ route('frontend.users.show', $game->account->user) }}">
                                    {{ $game->account->user->name }}
                                </a>
                            </td>
                            <td data-title="{{ __('Game') }}">
                                <a href="{{ route('backend.games.show', $game) }}">
                                    {{ __('app.game_' . class_basename(str_replace('GameMultiSlots', 'GameSlots', $game->gameable_type))) }}
                                </a>
                            </td>
                            <td data-title="{{ __('Net loss') }}" class="text-right">{{ $game->_net_win }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection