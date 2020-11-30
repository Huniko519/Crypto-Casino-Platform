@extends('frontend.layouts.home')

@section('content')
    @if(!empty($slider->slides))
        <div id="slider" class="carousel slide {{ $slider->animation == 'fade' ? 'carousel-fade' : '' }} mb-5" data-ride="carousel" data-interval="{{ $slider->interval * 1000 }}">
            @if($slider->indicators && count($slider->slides) > 1)
                <ol class="carousel-indicators">
                    @foreach($slider->slides as $i => $slide)
                        <li data-target="#slider" data-slide-to="{{ $i }}" class="{{ $i==0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
            @endif
            <div class="carousel-inner">
                @foreach($slider->slides as $i => $slide)
                    <div class="carousel-item {{ $i==0 ? 'active' : '' }}">
                        <img src="{{ url($slide->image->url) }}" class="d-block w-100">
                        <div class="carousel-caption d-none d-md-block">
                            <h2 class="display-3 text-light">{{ $slide->title }}</h2>
                            <p class="lead mt-4 mb-4 text-light">
                                {{ $slide->subtitle }}
                            </p>
                            @if($slide->link->url)
                                <a href="{{ url($slide->link->url) }}" class="{{ $slide->link->class }}">{{ $slide->link->title }}</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($slider->controls && count($slider->slides) > 1)
                <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">{{ __('Previous') }}</span>
                </a>
                <a class="carousel-control-next" href="#slider" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">{{ __('Next') }}</span>
                </a>
            @endif
        </div>
    @endif

    <div id="call-to-action-blocks" class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }}">
        <div class="row">
            <div class="col-lg-4 mt-3 mb-5">
                <div class="bg-secondary shadow-sm p-3">
                    <h2>{{ __('Free trial') }}</h2>
                    <p>
                        {{ __('Sign up and get :x free credits to play and try our casino.', ['x' => config('settings.bonuses.sign_up_credits')]) }}
                    </p>
                    @auth
                        <a href="{{ route('frontend.users.show', auth()->user()) }}" class="btn btn-primary">{{ __('My profile') }}</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Sign up') }}</a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-4 mt-3 mb-5">
                <div class="bg-secondary shadow-sm p-3">
                    <h2>{{ __('Crypto deposits') }}</h2>
                    <p>
                        {{ __('Make deposits in cryptocurrencies.') }}
                        @installed('payments')
                        @if(config('settings.bonuses.deposit.amount_pct') > 0)
                            {{ __('Get :pct% back when you deposit more than :amount credits at once.', [
                                    'amount' => config('settings.bonuses.deposit.amount_min'),
                                    'pct' => config('settings.bonuses.deposit.amount_pct'),
                                ])
                            }}
                        @endif
                        @endinstalled
                    </p>
                    @auth
                        <a href="{{ route('frontend.users.show', auth()->user()) }}" class="btn btn-primary">{{ __('My profile') }}</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Sign up') }}</a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-4 mt-3 mb-5">
                <div class="bg-secondary shadow-sm p-3">
                    <h2>{{ __('Referral program') }}</h2>
                    <p>
                        {{ __('Refer other people to our casino and get bonuses when they sign up, play games or make deposits.') }}
                    </p>
                    @auth
                        <a href="{{ route('frontend.users.show', auth()->user()) }}" class="btn btn-primary">{{ __('My profile') }}</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">{{ __('Sign up') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} pt-5 pb-5">
        <hr class="bg-primary">
    </div>

    <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }}">
        <div class="row">
            <div class="col text-center">
                <h2>{{ __('Play our exciting games') }}</h2>
            </div>
        </div>
    </div>

    <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }}">
        @if(!$game_categories->isEmpty())
            <div class="text-center mt-4">
                <a href="#!" class="btn btn-sm btn-outline-info mr-1 mr-md-3" onclick="filterByCategory('')">{{ __('All') }}</a>
                @foreach($game_categories as $category)
                    <a href="#!" class="btn btn-sm btn-outline-info mr-1 mr-md-3" onclick="filterByCategory('{{ $category }}')">{{ $category }}</a>
                @endforeach
            </div>
        @endif
        <div id="games-grid" class="row mt-5">
            @foreach($game_packages as $i => $game)
                <div class="col-12 col-md-4 mb-5 game" data-groups='{{ json_encode($game->categories) }}'>
                    <div class="card bg-secondary shadow-sm h-100">
                        <a href="{{ $game->url }}">
                            <img src="{{ $game->banner_url }}" class="card-img-top" alt="{{ $game->name }}">
                        </a>
                        <div class="card-body">
                            <h4 class="card-title">{{ $game->name }}</h4>
                            <a href="{{ $game->url }}" class="btn btn-primary mt-2">{{ __('Play') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @installed('raffle')
    <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} pt-3 pb-3">
        <hr class="bg-primary">
    </div>

    <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} mt-5 mb-5">
        <div class="row">
            <div class="col text-center mb-3">
                <h2>{{ __('Take part in raffles') }}</h2>
                <p class="lead my-4">
                    {{ __('We run raffles for all our users on a regular basis.') }}
                    {{ __('Purchase tickets and win hefty bonuses.') }}
                </p>
                <a href="{{ route('frontend.raffle.index') }}" class="btn btn-primary">{{ __('Purchase tickets') }}</a>
            </div>
        </div>
    </div>
    @endinstalled

    <div class="jumbotron jumbotron-fluid bg-secondary text-info">
        <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} text-center text-lg-left">
            <h2 class="display-4">
                <i class="fas fa-shield-alt"></i>
                {{ __('Provably fair') }}
            </h2>
            <p class="lead">
                {{ __('Our casino uses provably fair technology, which allows you to verify that each roll or card draw is completely random and you are not being cheated!') }}
            </p>
            <div class="mt-5">
                <a href="{{ url('page/provably-fair') }}" class="btn btn-info btn-lg">{{ __('Learn more') }}</a>
            </div>
        </div>
    </div>

    <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} mt-5 mb-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="bg-secondary p-4">
                    <h2>{{ __('Recent games') }}</h2>
                    @if(!$games->isEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($games as $game)
                                <li class="list-group-item d-md-flex justify-content-between align-items-center">
                                    <div>
                                        <h5>{{ $game->title }}</h5>
                                        <p class="card-text mb-1">{{ $game->gameable->result }}</p>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                {{ __('Played by') }}
                                                <a href="{{ route('frontend.users.show', $game->account->user) }}">{{ $game->account->user->name }}</a>
                                                {{ $game->created_at->diffForHumans() }}
                                            </small>
                                        </p>
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
                        <div>
                            {{ __('No games were played yet.') }}
                        </div>
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('frontend.history.recent') }}" class="btn btn-primary">{{ __('More recent games') }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0">
                <img src="{{ asset('images/home/mac-slots.png') }}" class="img-fluid">
            </div>
        </div>
    </div>

    @if($top_game)
        <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} pt-3 pb-3">
            <hr class="bg-primary">
        </div>

        <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} mt-5 mb-5">
            <div class="row">
                <div class="col col-lg-6 offset-lg-3 mb-5 text-center">
                    <div class="card text-center border border-primary">
                        <div class="card-header border-bottom border-primary bg-primary">
                            <h2 class="m-0">
                                <i class="fas fa-trophy"></i>
                                {{ __('Biggest win') }}
                            </h2>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $top_game->title }}</h5>
                            <p class="card-text">
                                <a href="{{ route('frontend.users.show', $top_game->account->user) }}">{{ $top_game->account->user->name }}</a>
                                {{ __('won :x credits', ['x' => $top_game->_win]) }}
                            </p>
                            <a href="{{ route('frontend.leaderboard') }}" class="btn btn-primary">{{ __('View leaderboard') }}</a>
                        </div>
                        <div class="card-footer text-muted border-top border-primary">
                            {{ $top_game->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="col mb-5"></div>
@endsection

@push('scripts')
<script>
    var shuffleInstance = new Shuffle(document.getElementById('games-grid'), {
        itemSelector: '.game'
    });

    function filterByCategory(category) {
        shuffleInstance.filter(category);
    }
</script>
@endpush
