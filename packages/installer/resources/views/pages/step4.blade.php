@extends('installer::layouts.install')

@section('content')
    <p>Congratulations! Installation is completed and <b>{{ __('Crypto Casino') }}</b> is now up and running.</p>
    <p>
        In order to have necessary application services run automatically in background
        please set up the following cron job:
    </p>
    <div class="alert alert-info mb-3">
        <pre class="mb-0">* * * * * {{ PHP_BINDIR . DIRECTORY_SEPARATOR }}php -d register_argc_argv=On {{ base_path() }}/artisan schedule:run >> /dev/null 2>&1</pre>
    </div>
    <a href="{{ route('frontend.index') }}" class="btn btn-primary" target="_blank">Front page</a>
    <a href="{{ route('login') }}" class="btn btn-primary" target="_blank">Log in</a>
@endsection
