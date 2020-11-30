@extends('backend.layouts.main')

@section('title')
    {{ __('Account') }} {{ $account->id }} :: {{ __('Transactions') }}
@endsection

@section('content')
    @if($transactions->isEmpty())
        <div class="alert alert-info" role="alert">
            {{ __('No transactions found.') }}
        </div>
    @else
        <table class="table table-hover table-stackable">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th class="text-right">{{ __('Amount') }}</th>
                    <th class="text-right">{{ __('Balance') }}</th>
                    <th class="text-right"><i class="fas fa-arrow-down"></i> {{ __('Created') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td data-title="{{ __('ID') }}">
                            {{ $transaction->transactionable->id }}
                        </td>
                        <td data-title="{{ __('Type') }}">
                            {{ $transaction->transactionable->title }}
                        </td>
                        <td data-title="{{ __('Amount') }}" class="text-right {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $transaction->_amount }}
                        </td>
                        <td data-title="{{ __('Balance') }}" class="text-right">
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
