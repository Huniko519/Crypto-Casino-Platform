<title>{{ __('Crypto Casino') }} | @yield('title')</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
@include('backend.includes.styles')