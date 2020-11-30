<div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} px-0">
    <nav class="navbar navbar-expand-md navbar-dark">
        <a class="navbar-brand text-body" href="{{ route('backend.dashboard.index') }}">
            <img src="{{ File::exists(base_path('public/images/logo-udf.png')) ? asset('images/logo-udf.png') : asset('images/logo.png') }}" class="d-inline-block align-top" alt="{{ __('Crypto Casino') }}">
            {{ __('Crypto Casino') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbar">
            <div class="navbar-nav dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbar-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('Navigation') }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-dropdown">
                    <span class="dropdown-header"><strong>{{ __('Frontend') }}</strong></span>
                    <a class="dropdown-item" href="{{ route('frontend.index') }}">{{ __('Home') }}</a>
                    <div class="dropdown-divider"></div>
                    <span class="dropdown-header"><strong>{{ __('Backend') }}</strong></span>
                    <a class="dropdown-item" href="{{ route('backend.dashboard.index') }}">{{ __('Dashboard') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.users.index') }}">{{ __('Users') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.accounts.index') }}">{{ __('Accounts') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.transactions.index') }}">{{ __('Transactions') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.games.index') }}">{{ __('Games') }}</a>

                    @packageview('backend.includes.menu')

                    <a class="dropdown-item" href="{{ route('backend.addons.index') }}">{{ __('Add-ons') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.settings.index') }}">{{ __('Settings') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.maintenance.index') }}">{{ __('Maintenance') }}</a>
                    <a class="dropdown-item" href="{{ route('backend.license.index') }}">{{ __('License registration') }}</a>

                    <div class="dropdown-divider"></div>
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn dropdown-item">{{ __('Log out') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>
