<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'backend.dashboard.index' ? 'active' : '' }}" href="{{ route('backend.dashboard.index') }}">{{ __('Users') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'backend.dashboard.games' ? 'active' : '' }}" href="{{ route('backend.dashboard.games') }}">{{ __('Games') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'backend.dashboard.accounts' ? 'active' : '' }}" href="{{ route('backend.dashboard.accounts') }}">{{ __('Accounts') }}</a>
    </li>

    @packageview('backend.pages.dashboard.tabs')
</ul>