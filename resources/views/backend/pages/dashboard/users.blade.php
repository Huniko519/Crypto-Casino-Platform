@extends('backend.layouts.main')

@section('title')
    {{ __('Dashboard') }} :: {{ __('Users') }}
@endsection

@section('content')
    @include('backend.pages.dashboard.tabs')

    <div class="row text-center mt-3">
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Users count') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $users_count }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Signed up last 7 days') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $signed_up_last_week }}</h4>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4 mb-4">
            <div class="card border-primary">
                <div class="card-header border-primary bg-primary">{{ __('Last signed up') }}</div>
                <div class="card-body">
                    <h4 class="card-title m-0">{{ $last_signed_up_at }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12 mb-4">
            <h2 class="text-center">{{ __('Sign-ups by date') }}</h2>
            <time-series-chart :data="{{ json_encode($sign_ups_by_day) }}" :scrollbar="true" theme="{{ $settings->theme }}" class="time-series-chart"></time-series-chart>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-12 col-lg-6 mb-4">
            <h2 class="text-center">{{ __('Direct vs referral sign-ups') }}</h2>
            <pie-chart :data="{{ json_encode($sign_ups_by_source) }}" theme="{{ $settings->theme }}" class="pie-chart"></pie-chart>
        </div>
        <div class="col-sm-12 col-lg-6 mb-4">
            <h2 class="text-center">{{ __('Users by role') }}</h2>
            <pie-chart :data="{{ json_encode($users_by_role) }}" theme="{{ $settings->theme }}" class="pie-chart"></pie-chart>
        </div>
    </div>
@endsection