<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('backend.includes.head')
</head>
<body class="backend {{ str_replace('.','-',Route::currentRouteName()) }} layout-{{ config('settings.layout') }} theme-{{ config('settings.theme') }}">
    @includeWhen(config('settings.gtm_container_id'), 'frontend.includes.gtm-body')

    <div id="app">

        <div class="bg-primary">
            @include('backend.includes.header')
        </div>

        <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }} px-0">

            @message
            @endmessage

            <div id="content" class="bg-secondary">
                <div class="row">
                    <div class="col">
                        <h1 class="mb-3">
                            @yield('title')
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

        @include('backend.includes.footer')

    </div>

    @include('backend.includes.scripts')

</body>
</html>
