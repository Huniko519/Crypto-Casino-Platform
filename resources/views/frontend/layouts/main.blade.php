<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('frontend.includes.head')
</head>
<body class="{{ str_replace('.','-',Route::currentRouteName()) }} layout-{{ config('settings.layout') }} theme-{{ config('settings.theme') }} {{ \Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'games.') ? 'game-page' : '' }}">
    @includeWhen(config('settings.gtm_container_id'), 'frontend.includes.gtm-body')

    <div id="app">

        <div class="bg-primary">
            @includeFirst(['frontend.includes.header-udf','frontend.includes.header'])
        </div>

        <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} px-0">

            @message
            @endmessage

            <div id="content" class="bg-secondary">
                <div class="row">
                    <div class="col">
                        <h1 class="mb-3">
                            @yield('title')
                            @yield('title_extra')
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        @yield('content')
                    </div>
                </div>
            </div>

        </div>

        @includeFirst(['frontend.includes.footer-udf', 'frontend.includes.footer'])

    </div>

    @include('frontend.includes.scripts')

</body>
</html>
