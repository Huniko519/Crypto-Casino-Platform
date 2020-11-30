<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('frontend.includes.head')
</head>
<body class="{{ str_replace('.','-',Route::currentRouteName()) }} theme-{{ config('settings.theme') }}">
    @includeWhen(config('settings.gtm_container_id'), 'frontend.includes.gtm-body')

    <div id="app">

        <div class="bg-primary">
            @includeFirst(['frontend.includes.header-udf','frontend.includes.header'])
        </div>

        @yield('content')

        @includeFirst(['frontend.includes.footer-udf','frontend.includes.footer'])

    </div>

    @include('frontend.includes.scripts')

</body>
</html>
