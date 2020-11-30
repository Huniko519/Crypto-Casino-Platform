@extends('backend.layouts.main')

@section('title')
    {{ __('Add-ons') }}
@endsection

@section('content')
    @foreach($packages as $package)
        <div class="card border-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $package->name }}</h5>
                <p class="card-text text-muted">{{ $package->description }}</p>
                @if($package->installed)
                    @if($package->enabled)
                        @if(isset($releases->{'add-ons'}->{$package->id}))
                            @if(version_compare($releases->{'add-ons'}->{$package->id}->version, $package->version, '>'))
                                <p class="text-warning mt-3">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ __('New version :v is available.', ['v' => $releases->{'add-ons'}->{$package->id}->version]) }}
                                </p>
                            @endif
                        @endif
                        <div class="btn-group" role="group">
                            <button class="btn btn-success">
                                {{ __('Enabled v:v', ['v' => $package->version]) }}
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu">
                                    <form method="POST" action="{{ route('backend.addons.disable', $package->id) }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            {{ __('Disable') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if(isset($releases->{'add-ons'}->{$package->id}))
                            @if(version_compare($releases->{'add-ons'}->{$package->id}->version, $package->version, '>'))
                                <a href="{{ route('backend.addons.install.show', $package->id) }}" class="btn btn-primary">{{ __('Upgrade to v:v', ['v' => $releases->{'add-ons'}->{$package->id}->version]) }}</a>
                            @endif
                        @endif
                    @else
                        <div class="btn-group" role="group">
                            <button class="btn btn-danger">
                                {{ __('Disabled') }}
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu">
                                    <form method="POST" action="{{ route('backend.addons.enable', $package->id) }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            {{ __('Enable') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if(isset($package->min_app_version) && version_compare(config('app.version'), $package->min_app_version, '<'))
                            <p class="card-text text-danger mt-3">{{ __('Main app version should be at least :v1 (:v2 installed)', ['v1' => $package->min_app_version, 'v2' => config('app.version')]) }}</p>
                        @endif
                    @endif
                    <div class="mt-3">
                        <a href="{{ route('backend.addons.changelog', $package->id) }}">{{ __('Changelog') }}</a>
                    </div>
                @elseif($package->purchase_url)
                    <a href="{{ $package->purchase_url }}" class="btn btn-primary" target="_blank">{{ __('Purchase') }}</a>
                    <a href="{{ route('backend.addons.install.show', $package->id) }}" class="btn btn-primary">{{ __('Install') }}</a>
                @endif
            </div>
        </div>
    @endforeach
@endsection
