@extends('backend.layouts.main')

@section('title')
    {{ __('Transactions') }}
@endsection

@section('content')
    @if($transactions->isEmpty())
        <div class="alert alert-info" role="alert">
            {{ __('No transactions found.') }}
        </div>
    @else
        <div class="btn-group mb-4" role="group" aria-label="{{ __('All types') }}">
            @if(request()->query('type'))
                @foreach($types as $type => $title)
                    @if(request()->query('type') == $type)
                        <a href="{{ route('backend.transactions.index', array_merge(request()->query(), ['type' => $type])) }}" class="btn btn-primary">{{ $title }}</a>
                    @endif
                @endforeach
            @else
                <a class="btn btn-primary" href="{{ route('backend.transactions.index', array_merge(request()->query(), ['type' => NULL])) }}">{{ __('All types') }}</a>
            @endif
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('backend.transactions.index', array_merge(request()->query(), ['type' => NULL])) }}">{{ __('All types') }}</a>
                <div class="dropdown-divider"></div>
                @foreach($types as $type => $title)
                    <a class="dropdown-item" href="{{ route('backend.transactions.index', array_merge(request()->query(), ['type' => $type])) }}">{{ $title }}</a>
                @endforeach
            </div>
        </div>
        <table class="table table-hover table-stackable">
            <thead>
                <tr>
                    @component('components.tables.sortable-column', ['id' => 'id', 'sort' => $sort, 'order' => $order])
                        {{ __('ID') }}
                    @endcomponent
                    <th>
                        <a href="#">{{ __('User') }}</a>
                    </th>
                    @component('components.tables.sortable-column', ['id' => 'type', 'sort' => $sort, 'order' => $order])
                        {{ __('Type') }}
                    @endcomponent
                    @component('components.tables.sortable-column', ['id' => 'amount', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                        {{ __('Amount') }}
                    @endcomponent
                    @component('components.tables.sortable-column', ['id' => 'created', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                        {{ __('Created') }}
                    @endcomponent
                    @component('components.tables.sortable-column', ['id' => 'updated', 'sort' => $sort, 'order' => $order, 'class' => 'text-right'])
                        {{ __('Updated') }}
                    @endcomponent
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td data-title="{{ __('ID') }}">{{ $transaction->id }}</td>
                        <td data-title="{{ __('User') }}">
                            <a href="{{ route('backend.users.edit', $transaction->account->user) }}">
                                {{ $transaction->account->user->name }}
                            </a>
                            @if($transaction->account->user->role == App\Models\User::ROLE_ADMIN)
                                <span class="badge badge-danger">{{ __('app.user_role_'.$transaction->account->user->role) }}</span>
                            @elseif($transaction->account->user->role == App\Models\User::ROLE_BOT)
                                <span class="badge badge-info text-light">{{ __('app.user_role_'.$transaction->account->user->role) }}</span>
                            @endif
                        </td>
                        <td data-title="{{ __('Type') }}">{{ $transaction->transactionable->title ?? '' }}</td>
                        <td data-title="{{ __('Amount') }}" class="text-right">{{ $transaction->_amount }}</td>
                        <td data-title="{{ __('Created') }}" class="text-right">
                            {{ $transaction->created_at->diffForHumans() }}
                            <span data-balloon="{{ $transaction->created_at }}" data-balloon-pos="up">
                                <i class="far fa-clock"></i>
                            </span>
                        </td>
                        <td data-title="{{ __('Updated') }}" class="text-right">
                            {{ $transaction->updated_at->diffForHumans() }}
                            <span data-balloon="{{ $transaction->updated_at }}" data-balloon-pos="up">
                                <i class="far fa-clock"></i>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
