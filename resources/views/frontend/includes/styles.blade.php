<link rel="stylesheet" type="text/css" href="{{ mix('css/' . $settings->theme . '.css') }}">
@stack('styles')
@if(file_exists(public_path('css/style-udf.css')))
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style-udf.css') }}">
@endif