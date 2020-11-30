@extends('backend.layouts.main')

@section('title')
    {{ __('Games') }}
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
                @component('components.tables.sortable-column', ['id' => 'id', 'sort' => $sort, 'order' => $order])
                    {{ __('ID') }}
                @endcomponent
                <th>
                    <a href="#">{{ __('User') }}</a>
                </th>
                @component('components.tables.sortable-column', ['id' => 'game', 'sort' => $sort, 'order' => $order])
                    {{ __('Game') }}
                @endcomponent
                <th>
                    <a href="#">{{ __('Result') }}</a>
                </th>
                @component('components.tables.sortable-column', ['id' => 'bet', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                    {{ __('Bet') }}
                @endcomponent
                @component('components.tables.sortable-column', ['id' => 'win', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                    {{ __('Win') }}
                @endcomponent
                @component('components.tables.sortable-column', ['id' => 'played', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                    {{ __('Played') }}
                @endcomponent
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($games as $game)
                <tr>
                    <td data-title="{{ __('ID') }}">{{ $game->id }}</td>
                    <td data-title="{{ __('User') }}">
                        <a href="{{ route('backend.users.edit', $game->account->user) }}">
                            {{ $game->account->user->name }}
                        </a>
                        @if($game->account->user->role == App\Models\User::ROLE_ADMIN)
                            <span class="badge badge-danger">{{ __('app.user_role_'.$game->account->user->role) }}</span>
                        @elseif($game->account->user->role == App\Models\User::ROLE_BOT)
                            <span class="badge badge-info text-light">{{ __('app.user_role_'.$game->account->user->role) }}</span>
                        @endif
                    </td>
                    <td data-title="{{ __('Game') }}">
                        {{ $game->title }}
                    </td>
                    <td data-title="{{ __('Result') }}">
                        {{ $game->gameable->result }}
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
                    <td class="text-right">
                        <a href="{{ route('backend.games.show', array_merge(['game' => $game], request()->query())) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-eye"></i>
                            {{ __('View') }}
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $games->appends(['sort' => $sort, 'order' => $order, 'uid' => request()->query('uid')])->links() }}
        </div>
    @endif
@endsection