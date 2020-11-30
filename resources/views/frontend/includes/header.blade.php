<div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} px-0">
    <nav class="navbar navbar-expand-md navbar-dark">
        <a class="navbar-brand text-body" href="{{ route('frontend.index') }}">
            <img src="{{ File::exists(base_path('public/images/logo-udf.png')) ? asset('images/logo-udf.png') : asset('images/logo.png') }}" class="d-inline-block align-top" alt="{{ __('Crypto Casino') }}">
            {{ __('Crypto Casino') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbar">
            <div class="navbar-nav flex-grow-1">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Games') }}
                    </a>
                    <div id="menu-dropdown-games" class="dropdown-menu">
                        @installed('game-heads-or-tails')
                            <a class="dropdown-item" href="{{ route('games.heads-or-tails.show') }}">{{ __('Heads or Tails') }}</a>
                        @endinstalled
                        @installed('game-slots')
                            <a class="dropdown-item" href="{{ route('games.slots.show') }}">{{ __('Slots') }}</a>
                        @endinstalled
                        @installed('game-multi-slots')
                            @foreach(config('game-multi-slots.titles') as $index => $title)
                                @if($title)
                                    <a class="dropdown-item" href="{{ route('games.multi-slots.show', ['index' => config('game-multi-slots.slugs')[$index] ?? $index]) }}">{{ __($title) }}</a>
                                @endif
                            @endforeach
                        @endinstalled
                        @installed('game-lucky-wheel')
                            @foreach(config('game-lucky-wheel.variations') as $index => $game)
                                @if($game->title)
                                    <a class="dropdown-item" href="{{ route('games.lucky-wheel.show', ['index' => $game->slug]) }}">{{ __($game->title) }}</a>
                                @endif
                            @endforeach
                        @endinstalled
                        @installed('game-dice')
                            <a class="dropdown-item" href="{{ route('games.dice.show') }}">{{ __('Dice') }}</a>
                        @endinstalled
                        @installed('game-dice-3d')
                            <a class="dropdown-item" href="{{ route('games.dice-3d.show') }}">{{ __('Dice 3D') }}</a>
                        @endinstalled
                        @installed('game-blackjack')
                            <a class="dropdown-item" href="{{ route('games.blackjack.show') }}">{{ __('Blackjack') }}</a>
                        @endinstalled
                        @installed('game-baccarat')
                            <a class="dropdown-item" href="{{ route('games.baccarat.show') }}">{{ __('Baccarat') }}</a>
                        @endinstalled
                        @installed('game-casino-holdem')
                            <a class="dropdown-item" href="{{ route('games.casino-holdem.show') }}">{{ __('Casino Holdem') }}</a>
                        @endinstalled
                        @installed('game-video-poker')
                            <a class="dropdown-item" href="{{ route('games.video-poker.show') }}">{{ __('Video Poker') }}</a>
                        @endinstalled
                        @installed('game-american-roulette')
                        <a class="dropdown-item" href="{{ route('games.american-roulette.show') }}">{{ __('American Roulette') }}</a>
                        @endinstalled
                        @installed('game-roulette')
                            <a class="dropdown-item" href="{{ route('games.roulette.show') }}">{{ __('European Roulette') }}</a>
                        @endinstalled
                        @installed('game-american-bingo')
                            <a class="dropdown-item" href="{{ route('games.american-bingo.show') }}">{{ __('75 Ball Bingo') }}</a>
                        @endinstalled
                        @installed('game-keno')
                            <a class="dropdown-item" href="{{ route('games.keno.show') }}">{{ __('Keno') }}</a>
                        @endinstalled
                    </div>
                </div>
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('History') }}
                    </a>
                    <div id="menu-dropdown-history" class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('frontend.history.recent') }}">{{ __('Recent games') }}</a>
                        <a class="dropdown-item" href="{{ route('frontend.history.top-wins') }}">{{ __('Top wins') }}</a>
                        <a class="dropdown-item" href="{{ route('frontend.history.top-losses') }}">{{ __('Top losses') }}</a>
                        @auth
                            <a class="dropdown-item" href="{{ route('frontend.history.my') }}">{{ __('My games') }}</a>
                        @endauth
                    </div>
                </div>
                @installed('raffle')
                    <a class="nav-item nav-link" href="{{ route('frontend.raffle.index') }}">{{ __('Raffle') }}</a>
                @endinstalled
                @installed('multi-raffle')
                    @packageview('frontend.includes.header')
                @endinstalled
                <a class="nav-item nav-link" href="{{ route('frontend.leaderboard') }}">{{ __('Leaderboard') }}</a>
                @if(config('broadcasting.connections.pusher.key'))
                    <a class="nav-item nav-link" href="{{ route('frontend.chat.index') }}">{{ __('Chat') }}</a>
                @endif
            </div>
            @guest
                <div class="navbar-nav">
                    <a href="{{ route('login') }}" class="btn text-white-50 mr-2">{{ __('Log in') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-info text-white">{{ __('Sign up') }}</a>
                </div>
            @endguest
            @auth
                <locale-select :locales="{{ json_encode($locale->locales()) }}" :locale="{{ json_encode($locale->locale()) }}"></locale-select>
                <div class="navbar-nav dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @admin
                            <a class="dropdown-item" href="{{ route('backend.dashboard.index') }}">
                                <i class="fas fa-cogs"></i>
                                {{ __('Backend') }}
                            </a>
                            <div class="dropdown-divider"></div>
                        @endadmin
                        <a class="dropdown-item" href="{{ route('frontend.users.show', auth()->user()) }}">
                            <i class="far fa-user"></i>
                            {{ __('Profile') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('frontend.account.show') }}">
                            <i class="fas fa-wallet"></i>
                            {{ __('Account') }}
                        </a>

                        @packageview('frontend.includes.menu')

                        <a class="dropdown-item" href="{{ route('frontend.bonuses.index') }}">
                            <i class="fas fa-money-bill-alt"></i>
                            {{ __('Bonuses') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('frontend.security.index') }}">
                            <i class="fas fa-shield-alt"></i>
                            {{ __('Security') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('frontend.users.password.edit') }}">
                            <i class="fas fa-key"></i>
                            {{ __('Change password') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn dropdown-item">
                                <i class="fas fa-sign-out-alt"></i>
                                {{ __('Log out') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>
</div>
