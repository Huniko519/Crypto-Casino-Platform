@if(Route::currentRouteName() == 'frontend.index')
    <title>{{ __('Crypto Casino') }} | {{ __('Bet and win crypto') }}</title>
@else
    <title>{{ __('Crypto Casino') }} | @yield('title')</title>
@endif
<!-- {{ config('app.version') }} -->
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="{{ __('Crypto casino games') }}" />
<meta name="keywords" content="{{ __('crypto,casino,slots,slot machine,betting,gambling') }}" />
<!-- Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">
<link rel="mask-icon" href="{{ asset('images/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
<link rel="shortcut icon" href="{{ asset('images/favicon/favicon.ico') }}">
<meta name="msapplication-TileColor" content="#9f00a7">
<meta name="msapplication-config" content="{{ asset('images/favicon/browserconfig.xml') }}">
<meta name="theme-color" content="#ffffff">
<!-- END Favicon -->
<!--Open Graph tags-->
<meta property="og:url" content="{{ url('/') }}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ __('Crypto Casino') }}" />
<meta property="og:description" content="{{ __('Casino, where you can bet and get paid in cryptocurrencies.') }}" />
<meta property="og:image" content="{{ File::exists(base_path('public/images/og-image-udf.jpg')) ? asset('images/og-image-udf.jpg') : asset('images/og-image.jpg') }}" />
<!--END Open Graph tags-->
@includeWhen(config('settings.gtm_container_id'), 'frontend.includes.gtm-head')
@include('frontend.includes.styles')
