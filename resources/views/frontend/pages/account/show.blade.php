@extends('frontend.layouts.main')

@section('title')
    {{ __('Account') }}
@endsection

@section('content')
    <table class="table table-hover table-stackable">
        <thead>
            <tr>
                <th>
                    {{ __('Balance') }}
                    <span data-balloon="{{ __('in credits') }}" data-balloon-pos="up">
                        <i class="far fa-question-circle"></i>
                    </span>
                </th>
                <th class="text-right">{{ __('Created') }}</th>
                <th class="text-right">{{ __('Updated') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td data-title="{{ __('Balance') }}">{{ $account->_balance }}</td>
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
                    @packageview('frontend.includes.account.actions')
                </td>
            </tr>
        </tbody>
    </table>
    @if(!$transactions->isEmpty())
        <h2>{{ __('Transactions') }}</h2>
        <table class="table table-hover table-stackable">
            <thead>
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Type') }}</th>
                <th class="text-right">
                    {{ __('Amount') }}
                    <span data-balloon="{{ __('in credits') }}" data-balloon-pos="up">
                        <i class="far fa-question-circle"></i>
                    </span>
                </th>
                <th class="text-right">
                    {{ __('Running balance') }}
                    <span data-balloon="{{ __('in credits') }}" data-balloon-pos="up">
                        <i class="far fa-question-circle"></i>
                    </span>
                </th>
                <th class="text-right">{{ __('Created') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td data-title="{{ __('ID') }}">
                        {{ $transaction->transactionable_id }}
                    </td>
                    <td data-title="{{ __('Type') }}">{{ $transaction->transactionable->title ?? class_basename($transaction->transactionable_type) }}</td>
                    <td data-title="{{ __('Amount') }}" class="text-right {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->_amount }}
                    </td>
                    <td data-title="{{ __('Running balance') }}" class="text-right">
                        {{ $transaction->_balance }}
                        <i class="fas fa-long-arrow-alt-{{ $transaction->amount > 0 ? 'up text-success' : 'down text-danger' }}"></i>
                    </td>
                    <td data-title="{{ __('Created') }}" class="text-right">
                        {{ $transaction->created_at->diffForHumans() }}
                        <span data-balloon="{{ $transaction->created_at }}" data-balloon-pos="up">
                            <i class="far fa-clock"></i>
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>
    @endif
@endsection
