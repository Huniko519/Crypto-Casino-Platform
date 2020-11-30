@extends('backend.layouts.main')

@section('title')
    {{ __('Maintenance') }}
@endsection

@section('content')
    <div class="mb-3">
        <span class="badge badge-primary p-2 mr-2">{{ __('System info') }}</span>{{ $system_info }}
    </div>
    <div class="card border-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ __('Application version') }}</h5>
            @if(version_compare($releases->app->version, config('app.version'), '>'))
                <span class="text-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('New version :v is available.', ['v' => $releases->app->version]) }}
                </span>
            @else
                <span class="text-success">
                    <i class="fas fa-check"></i>
                    {{ __('The latest application release is installed.') }}
                </span>
            @endif
        </div>
    </div>
    <div class="card border-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ __('Add-ons') }}</h5>
            @if(!empty($outdated_addons))
                <div class="text-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('The following add-ons can be upgraded: :list.', ['list' => implode(', ', array_column($outdated_addons, 'name'))]) }}
                </div>
                <div class="mt-3">
                    <a href="{{ route('backend.addons.index') }}" class="btn btn-primary">{{ __('Add-ons') }}</a>
                </div>
            @else
                <span class="text-success">
                    <i class="fas fa-check"></i>
                    {{ __('All add-ons are up-to-date.') }}
                </span>
            @endif
        </div>
    </div>
    <div class="card border-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ __('Maintenance mode') }}</h5>
            <p class="text-muted">{{ __('Enable maintenance mode when you are upgrading the application, installing add-ons or doing other service tasks. All users except for administrators will not be able to use the website.') }}</p>
            @if(!app()->isDownForMaintenance())
                <p class="text-muted">{{ __('Maintenance mode is currently disabled.') }}</p>
                <form method="POST" action="{{ route('backend.maintenance.enable') }}">
                    @csrf
                    <div class="input-group">
                        <input name="message" type="text" class="form-control" value="{{ __('Sorry, we are doing some maintenance. Please check back soon.') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">{{ __('Enable') }}</button>
                        </div>
                    </div>
                </form>
            @else
                <p class="text-muted">{{ __('Maintenance mode is currently enabled.') }}</p>
                <form method="POST" action="{{ route('backend.maintenance.disable') }}">
                    @csrf
                    <button class="btn btn-primary" type="submit">{{ __('Disable') }}</button>
                </form>
            @endif
        </div>
    </div>
    <div class="card border-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ __('Cache') }}</h5>
            <p class="text-muted">{{ __('Application templates, config files, translation strings are cached. It is necessary to clear cache when making any changes to the source code or installing upgrades.') }}</p>
            <form method="POST" action="{{ route('backend.maintenance.cache') }}">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('Clear cache') }}</button>
            </form>
        </div>
    </div>
    <div class="card border-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ __('Database updates') }}</h5>
            <p class="text-muted">{{ __('When upgrading to a new version of the application it is essentially to run database updates.') }}</p>
            <form method="POST" action="{{ route('backend.maintenance.migrate') }}">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('Run database updates') }}</button>
            </form>
        </div>
    </div>
    <div class="card border-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ __('Tasks') }}</h5>
            <p class="text-muted">{{ __('The app provides a number of commands, which can be executed to perform certain actions.') }}</p>
            <form method="POST" action="{{ route('backend.maintenance.task') }}">
                @csrf
                <div class="form-group">
                    <select name="command" class="custom-select">
                        @foreach($commands as $command)
                            <option value="{{ $command['class'] }}">{{ $command['description'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('Execute task') }}</button>
            </form>
        </div>
    </div>
    <div class="card border-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ __('Cron') }}</h5>
            <p class="text-muted">{{ __('Certain tasks should run on a regular basis. In order for the application to execute them automatically you should add the following system cron job:') }}</p>
            <pre class="alert alert-info">* * * * * {{ PHP_BINDIR . DIRECTORY_SEPARATOR }}php -d register_argc_argv=On {{ base_path() }}/artisan schedule:run >> /dev/null 2>&1</pre>
            <p class="text-muted">
                {{ __('Please note that the command-line PHP version on your server should also meet the PHP version requirements, otherwise the cron job will fail to execute.') }}
                {{ __('On some servers with multi-PHP versions support you might need to explicitly specify the path to the proper version of PHP.') }}
            </p>
            <form method="POST" action="{{ route('backend.maintenance.cron') }}">
                @csrf
                <button type="submit" class="btn btn-primary">{{ __('Run cron job manually') }}</button>
            </form>
        </div>
    </div>
    <div class="card border-primary mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ __('Logs') }}</h5>
            @if($log_size)
                <p class="text-muted">{{ __('Application log file size is :n MB.', ['n' => sprintf('%.2f', $log_size)]) }}</p>
                <a href="{{ route('backend.maintenance.log.view') }}" class="btn btn-primary" target="_blank">{{ __('View') }}</a>
                <a href="{{ route('backend.maintenance.log.download') }}" class="btn btn-primary" target="_blank">{{ __('Download') }}</a>
            @else
                <p class="text-muted">{{ __('No application log file found.') }}</p>
            @endif
        </div>
    </div>
@endsection
