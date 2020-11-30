@extends('backend.layouts.main')

@section('title')
    {{ __('Users') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 offset-lg-9 mb-3">
            @search(['route' => 'backend.users.index', 'search' => $search])
            @endsearch
        </div>
    </div>
    @if($users->isEmpty())
        <div class="alert alert-info" role="alert">
            {{ __('No users found.') }}
        </div>
    @else
        <table class="table table-hover table-stackable">
        <thead>
        <tr>
            @component('components.tables.sortable-column', ['id' => 'id', 'sort' => $sort, 'order' => $order])
                {{ __('ID') }}
            @endcomponent
            @component('components.tables.sortable-column', ['id' => 'name', 'sort' => $sort, 'order' => $order])
                {{ __('Name') }}
            @endcomponent
            @component('components.tables.sortable-column', ['id' => 'email', 'sort' => $sort, 'order' => $order])
                {{ __('Email') }}
            @endcomponent
            @component('components.tables.sortable-column', ['id' => 'games', 'sort' => $sort, 'order' => $order])
                {{ __('Games') }}
            @endcomponent
            @component('components.tables.sortable-column', ['id' => 'total_bet', 'sort' => $sort, 'order' => $order])
                {{ __('Bet') }}
            @endcomponent
            @component('components.tables.sortable-column', ['id' => 'status', 'sort' => $sort, 'order' => $order])
                {{ __('Status') }}
            @endcomponent
            @component('components.tables.sortable-column', ['id' => 'last_login_at', 'sort' => $sort, 'order' => $order, 'class' => 'right aligned'])
                {{ __('Last login at') }}
            @endcomponent
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td data-title="{{ __('ID') }}">{{ $user->id }}</td>
                <td data-title="{{ __('Name') }}">
                    <a href="{{ route('backend.users.edit', $user) }}">
                        {{ $user->name }}
                    </a>
                    @if($user->profiles)
                        @foreach($user->profiles as $profile)
                            <span data-balloon="{{ __('Linked :provider profile', ['provider' => ucfirst($profile->provider_name)]) }}" data-balloon-pos="up">
                                <i class="{{ config('services.login_providers.' . $profile->provider_name . '.icon') }}"></i>
                            </span>
                        @endforeach
                    @endif
                    @if($user->totp_secret)
                        <i class="fas fa-shield-alt text-info"></i>
                    @endif
                    @if($user->role == App\Models\User::ROLE_ADMIN)
                        <span class="badge badge-danger">{{ __('app.user_role_'.$user->role) }}</span>
                    @elseif($user->role == App\Models\User::ROLE_BOT)
                        <span class="badge badge-info text-light">{{ __('app.user_role_'.$user->role) }}</span>
                    @endif
                    @if($user->referrer)
                        <span data-balloon="{{ __('Referred by :user', ['user' => $user->referrer->name . ' (' . $user->referrer->email . ')']) }}" data-balloon-pos="up">
                            <i class="fas fa-retweet fa-sm" ></i>
                        </span>
                    @endif
                </td>
                <td data-title="{{ __('Email') }}">
                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                <td data-title="{{ __('Games') }}">{{ $user->games_count }}</td>
                <td data-title="{{ __('Bet') }}">{{ $user->_total_bet }}</td>
                <td data-title="{{ __('Status') }}">{{ __('app.user_status_' . $user->status) }}</td>
                <td data-title="{{ __('Last login at') }}">
                    {{ $user->last_login_at->diffForHumans() }}
                    <span data-balloon="{{ $user->last_login_at }}" data-balloon-pos="up">
                        <i class="far fa-clock" ></i>
                    </span>
                </td>
                <td class="text-right">
                    <div class="btn-group" role="group" aria-label="{{ __('Edit') }}">
                        <a href="{{ route('backend.users.edit', array_merge(['user' => $user], request()->query())) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-pen fa-sm"></i>
                            {{ __('Edit') }}
                        </a>
                        <div class="btn-group" role="group">
                            <button id="users-action-button" type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="dropdown-menu" aria-labelledby="users-action-button">
                                <a class="dropdown-item" href="{{ route('frontend.users.show', $user) }}">
                                    <i class="far fa-eye fa-sm"></i>
                                    {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('backend.games.index', ['uid' => $user->id]) }}">
                                    <i class="fas fa-dice fa-sm"></i>
                                    {{ __('Games') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('backend.users.edit', array_merge(['user' => $user], request()->query())) }}">
                                    <i class="fas fa-pen fa-sm"></i>
                                    {{ __('Edit') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('backend.users.delete', $user) }}">
                                    <i class="fas fa-trash fa-sm"></i>
                                    {{ __('Delete') }}
                                </a>
                                @if($user->role != App\Models\User::ROLE_BOT)
                                    <a class="dropdown-item" href="{{ route('backend.users.mail.show', array_merge(['user' => $user], request()->query())) }}">
                                        <i class="far fa-envelope fa-sm"></i>
                                        {{ __('Mail') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
        <div class="d-flex justify-content-center">
            {{ $users->appends(['search' => $search])->appends(['sort' => $sort])->appends(['order' => $order])->links() }}
        </div>
    @endif
@endsection
