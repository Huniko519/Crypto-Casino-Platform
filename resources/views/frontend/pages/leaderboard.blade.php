@extends('frontend.layouts.main')

@section('title')
    {{ __('Leaderboard') }}
@endsection

@section('content')
    <div class="btn-group mb-4" role="group" aria-label="{{ __('All games') }}">
        @if(Request::get('game') == 'HeadsOrTails')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'HeadsOrTails'])) }}" class="btn btn-primary">{{ __('Heads or Tails') }}</a>
        @elseif(Request::get('game') == 'Slots')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Slots'])) }}" class="btn btn-primary">{{ __('Slots') }}</a>
        @elseif(Request::get('game') == 'MultiSlots')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'MultiSlots'])) }}" class="btn btn-primary">{{ __(config('game-multi-slots.titles')[Request::get('index')] ?? 'Slots') }}</a>
        @elseif(Request::get('game') == 'LuckyWheel')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'LuckyWheel'])) }}" class="btn btn-primary">{{ __(config('game-lucky-wheel.variations')[Request::get('index')]->title ?? 'Lucky Wheel') }}</a>
        @elseif(Request::get('game') == 'Dice')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Dice'])) }}" class="btn btn-primary">{{ __('Dice') }}</a>
        @elseif(Request::get('game') == 'Dice 3D')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Dice3D'])) }}" class="btn btn-primary">{{ __('Dice 3D') }}</a>
        @elseif(Request::get('game') == 'Blackjack')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Blackjack'])) }}" class="btn btn-primary">{{ __('Blackjack') }}</a>
        @elseif(Request::get('game') == 'Baccarat')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Baccarat'])) }}" class="btn btn-primary">{{ __('Baccarat') }}</a>
        @elseif(Request::get('game') == 'CasinoHoldem')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'CasinoHoldem'])) }}" class="btn btn-primary">{{ __('Casino Holdem') }}</a>
        @elseif(Request::get('game') == 'VideoPoker')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'VideoPoker'])) }}" class="btn btn-primary">{{ __('Video Poker') }}</a>
        @elseif(Request::get('game') == 'AmericanRoulette')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'AmericanRoulette'])) }}" class="btn btn-primary">{{ __('American Roulette') }}</a>
        @elseif(Request::get('game') == 'Roulette')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Roulette'])) }}" class="btn btn-primary">{{ __('European Roulette') }}</a>
        @elseif(Request::get('game') == 'AmericanBingo')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'AmericanBingo'])) }}" class="btn btn-primary">{{ __('75 Ball Bingo') }}</a>
        @elseif(Request::get('game') == 'Keno')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Keno'])) }}" class="btn btn-primary">{{ __('Keno') }}</a>
        @else
            <a href="{{ route('frontend.leaderboard', request()->query()) }}" class="btn btn-primary">{{ __('All games') }}</a>
        @endif
        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => NULL, 'index' => NULL])) }}">{{ __('All games') }}</a>
            <div class="dropdown-divider"></div>
            @installed('game-heads-or-tails')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'HeadsOrTails'])) }}">
                {{ __('Heads or Tails') }}
            </a>
            @endinstalled
            @installed('game-slots')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Slots'])) }}">
                {{ __('Slots') }}
            </a>
            @endinstalled
            @installed('game-multi-slots')
                @foreach(config('game-multi-slots.titles') as $index => $title)
                    @if($title)
                        <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'MultiSlots', 'index' => $index])) }}">
                            {{ __($title) }}
                        </a>
                    @endif
                @endforeach
            @endinstalled
            @installed('game-lucky-wheel')
                @foreach(config('game-lucky-wheel.variations') as $index => $game)
                    @if($game->title)
                        <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'LuckyWheel', 'index' => $index])) }}">
                            {{ __($game->title) }}
                        </a>
                    @endif
                @endforeach
            @endinstalled
            @installed('game-dice')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Dice'])) }}">
                {{ __('Dice') }}
            </a>
            @endinstalled
            @installed('game-dice-3d')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Dice3D'])) }}">
                {{ __('Dice 3D') }}
            </a>
            @endinstalled
            @installed('game-blackjack')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Blackjack'])) }}">
                {{ __('Blackjack') }}
            </a>
            @endinstalled
            @installed('game-baccarat')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Baccarat'])) }}">
                {{ __('Baccarat') }}
            </a>
            @endinstalled
            @installed('game-casino-holdem')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'CasinoHoldem'])) }}">
                {{ __('Casino Holdem') }}
            </a>
            @endinstalled
            @installed('game-video-poker')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'VideoPoker'])) }}">
                {{ __('Video Poker') }}
            </a>
            @endinstalled
            @installed('game-american-roulette')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'AmericanRoulette'])) }}">
                {{ __('American Roulette') }}
            </a>
            @endinstalled
            @installed('game-roulette')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Roulette'])) }}">
                {{ __('European Roulette') }}
            </a>
            @endinstalled
            @installed('game-american-bingo')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'AmericanBingo'])) }}">
                {{ __('75 Ball Bingo') }}
            </a>
            @endinstalled
            @installed('game-keno')
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['game' => 'Keno'])) }}">
                {{ __('Keno') }}
            </a>
            @endinstalled
        </div>
    </div>
    <div class="btn-group mb-4" role="group" aria-label="{{ __('All time') }}">
        @if(Request::get('period') == 'CurrentWeek')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => 'CurrentWeek'])) }}" class="btn btn-primary">{{ __('Current week') }}</a>
        @elseif(Request::get('period') == 'CurrentMonth')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => 'CurrentMonth'])) }}" class="btn btn-primary">{{ __('Current month') }}</a>
        @elseif(Request::get('period') == 'PreviousMonth')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => 'PreviousMonth'])) }}" class="btn btn-primary">{{ __('Previous month') }}</a>
        @elseif(Request::get('period') == 'CurrentYear')
            <a href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => 'CurrentYear'])) }}" class="btn btn-primary">{{ __('Current year') }}</a>
        @else
            <a href="{{ route('frontend.leaderboard', request()->query()) }}" class="btn btn-primary">{{ __('All time') }}</a>
        @endif
        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => NULL])) }}">{{ __('All time') }}</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => 'CurrentWeek'])) }}">{{ __('Current week') }}</a>
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => 'CurrentMonth'])) }}">{{ __('Current month') }}</a>
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => 'PreviousMonth'])) }}">{{ __('Previous month') }}</a>
            <a class="dropdown-item" href="{{ route('frontend.leaderboard', array_merge(request()->query(), ['period' => 'CurrentYear'])) }}">{{ __('Current year') }}</a>
        </div>
    </div>
    @if($users->isEmpty())
        <div class="alert alert-info" role="alert">
            {{ __('No data found.') }}
        </div>
    @else
        <table class="table table-hover table-stackable">
            <thead>
            <tr>
                <th>{{ __('Rank') }}</th>
                <th>{{ __('Name') }}</th>
                <th class="text-right">{{ __('Games played') }}</th>
                <th class="text-right">{{ __('Total bet') }}</th>
                <th class="text-right">{{ __('Max win') }}</th>
                <th class="text-right">{{ __('Total net win') }}</th>
                <th class="text-right"><i class="fas fa-arrow-down"></i> {{ __('Total win') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $i=> $user)
                <tr>
                    <td data-title="{{ __('Rank') }}">
                        {{ ++$i + 10 * (max(1, intval(request()->query('page'))) - 1) }}
                    </td>
                    <td data-title="{{ __('Name') }}">
                        <a href="{{ route('frontend.users.show', $user) }}">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td data-title="{{ __('Games played') }}" class="text-right">
                        {{ $user->_total_games }}
                    </td>
                    <td data-title="{{ __('Total bet') }}" class="text-right">
                        {{ $user->_total_bet }}
                    </td>
                    <td data-title="{{ __('Max win') }}" class="text-right">
                        {{ $user->_max_win }}
                    </td>
                    <td data-title="{{ __('Total net win') }}" class="text-right">
                        {{ $user->_total_net_win }}
                    </td>
                    <td data-title="{{ __('Total win') }}" class="text-right">
                        {{ $user->_total_win }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
