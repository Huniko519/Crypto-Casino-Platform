@extends('backend.layouts.main')

@section('title')
    {{ __('Dashboard') }} :: {{ __('Accounts') }}
@endsection

@section('content')
    @include('backend.pages.dashboard.tabs')

    <div class="row text-center mt-3">
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Average balance') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $avg_balance }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Max balance') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $max_balance }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Total balance') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $total_balance }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12">
            <h2 class="text-center">{{ __('Transactions by type') }}</h2>
            <pie-chart :data="{{ json_encode($transactions_by_type) }}" theme="{{ $settings->theme }}" class="pie-chart"></pie-chart>
        </div>
    </div>
    @if(!$top_transactions->isEmpty())
        <div class="row mt-3">
            <div class="col-sm-12">
                <h2 class="text-center mb-4">{{ __('Top transactions') }}</h2>
                <table class="table table-hover table-stackable">
                    <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th class="text-right">{{ __('Amount') }}</th>
                        <th class="text-right">{{ __('Created') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($top_transactions as $transaction)
                        <tr>
                            <td data-title="{{ __('User') }}">
                                <a href="{{ route('frontend.users.show', $transaction->account->user) }}">
                                    {{ $transaction->account->user->name }}
                                </a>
                            </td>
                            <td data-title="{{ __('ID') }}">{{ $transaction->transactionable_id }}</td>
                            <td data-title="{{ __('Type') }}">{{ $transaction->transactionable->title ?? __('Unknown') }}</td>
                            <td data-title="{{ __('Amount') }}" class="text-right">{{ $transaction->_amount }}</td>
                            <td data-title="{{ __('Created') }}" class="text-right">{{ $transaction->updated_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
