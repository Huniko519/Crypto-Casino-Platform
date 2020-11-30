@extends('backend.layouts.main')

@section('title')
    {{ __('Accounts') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 offset-lg-9 mb-3">
            @search(['route' => 'backend.accounts.index', 'search' => $search])
            @endsearch
        </div>
    </div>
    @if($accounts->isEmpty())
        <div class="alert alert-info" role="alert">
            {{ __('No accounts found.') }}
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
                    @component('components.tables.sortable-column', ['id' => 'balance', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                        {{ __('Balance') }}
                    @endcomponent
                    @component('components.tables.sortable-column', ['id' => 'created', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                        {{ __('Created') }}
                    @endcomponent
                    @component('components.tables.sortable-column', ['id' => 'updated', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                        {{ __('Updated') }}
                    @endcomponent
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td data-title="{{ __('ID') }}">{{ $account->id }}</td>
                        <td data-title="{{ __('User') }}">
                            <a href="{{ route('backend.users.edit', $account->user) }}">
                                {{ $account->user->name }}
                            </a>
                            @if($account->user->role == App\Models\User::ROLE_ADMIN)
                                <span class="badge badge-danger">{{ __('app.user_role_'.$account->user->role) }}</span>
                            @elseif($account->user->role == App\Models\User::ROLE_BOT)
                                <span class="badge badge-info text-light">{{ __('app.user_role_'.$account->user->role) }}</span>
                            @endif
                        </td>
                        <td data-title="{{ __('Balance') }}" class="text-right">{{ $account->_balance }}</td>
                        <td data-title="{{ __('Created') }}" class="text-right">
                            {{ $account->created_at->diffForHumans() }}
                            <span data-balloon="{{ $account->created_at }}" data-balloon-pos="up">
                                <i class="far fa-clock"></i>
                            </span>
                        </td>
                        <td data-title="{{ __('Updated') }}" class="text-right">
                            {{ $account->updated_at->diffForHumans() }}
                            <span data-balloon="{{ $account->updated_at }}" data-balloon-pos="up">
                                <i class="far fa-clock"></i>
                            </span>
                        </td>
                        <td class="text-right">
                            <div class="btn-group" role="group" aria-label="{{ __('Actions') }}">
                                <a class="btn btn-primary btn-sm">
                                    {{ __('Actions') }}
                                </a>
                                <div class="btn-group" role="group">
                                    <button id="accounts-action-button" type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="accounts-action-button">
                                        <a class="dropdown-item" href="{{ route('backend.accounts.transactions', $account) }}">
                                            <i class="fas fa-list fa-sm"></i>
                                            {{ __('Transactions') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('backend.accounts.debit', $account) }}">
                                            <i class="fas fa-minus fa-sm"></i>
                                            {{ __('Debit') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('backend.accounts.credit', $account) }}">
                                            <i class="fas fa-plus fa-sm"></i>
                                            {{ __('Credit') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $accounts->appends(['search' => $search])->appends(['sort' => $sort])->appends(['order' => $order])->links() }}
        </div>
    @endif
@endsection
