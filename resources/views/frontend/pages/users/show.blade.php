@extends('frontend.layouts.main')

@section('title')
    {{ $user->name }}
@endsection

@section('title_extra')
    @auth
        @if($user->id == auth()->user()->id)
            <a href="{{ route('frontend.users.edit') }}" class="btn btn-primary">
                <i class="fas fa-pen"></i>
                {{ __('Edit') }}
            </a>
        @endif
    @endauth
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-4 mt-3">
            <div class="card border-primary text-center">
                <div class="card-body text-primary">
                    <h4 class="card-title">{{ __('Played :x games', ['x' => $total_played]) }}</h4>
                    <p class="card-text"><small class="text-muted">{{ __('Last played :t', ['t' => $last_played ? $last_played->diffForHumans() : __('never')]) }}</small></p>
                </div>
            </div>
            @if($total_played>0)
                <h3 class="text-center mt-4">{{ __('Games by result') }}</h3>
                <pie-chart :data="{{ json_encode($games_by_result) }}" theme="{{ $settings->theme }}" class="pie-chart"></pie-chart>
                <h3 class="text-center mt-4">{{ __('Games by type') }}</h3>
                <pie-chart :data="{{ json_encode($games_by_type) }}" theme="{{ $settings->theme }}" class="pie-chart"></pie-chart>
                <h3 class="text-center mt-4">{{ __('Max win by game') }}</h3>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{{ __('Game') }}</th>
                        <th class="text-right">{{ __('Max win') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($games_by_type->sortByDesc('max_win') as $game)
                        <tr>
                            <td>{{ $game['category'] }}</td>
                            <td class="text-right">{{ $game['_max_win'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="col-sm-12 col-lg-8 mt-3">
            <h3>{{ __('Recent games') }}</h3>
            @if(!$recent_games->isEmpty())
                <ul class="list-group list-group-flush">
                    @foreach($recent_games as $game)
                        <li class="list-group-item d-md-flex justify-content-between align-items-center">
                            <div>
                                <h5>{{ $game->title }}</h5>
                                <p class="card-text mb-1">{{ $game->gameable->result }}</p>
                                <p class="card-text"><small class="text-muted">{{ __('Played :t', ['t' => $game->updated_at->diffForHumans()]) }}</small></p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <span class="badge badge-primary badge-pill p-2">
                                    {{ __('Bet :x', ['x' => $game->_bet]) }}
                                </span>
                                <span class="badge badge-primary badge-pill p-2">
                                    {{ __('Win :x', ['x' => $game->_win]) }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info" role="alert">
                    {{ __('No games were played yet.') }}
                </div>
            @endif
        </div>
    </div>
@endsection