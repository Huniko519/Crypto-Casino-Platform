<div class="{{ config('settings.layout') == 'boxed' ? 'my-2' : 'my-0' }}">
    @if ($errors->any())
        <message :messages="{{ json_encode($errors->all()) }}" type="danger" heading="{{ __('Error') }}"></message>
    @elseif (session('error'))
        <message message="{{ session('error') }}" type="danger" heading="{{ __('Error') }}"></message>
    @elseif (session('warning'))
        <message message="{{ session('warning') }}" type="warning" heading="{{ __('Warning') }}"></message>
    @elseif (session('success'))
        <message message="{{ session('success') }}" type="success" heading="{{ __('Success') }}"></message>
    @elseif (session('status'))
        <message message="{{ session('status') }}" type="success" heading="{{ __('Success') }}"></message>
    @elseif (session('info'))
        <message message="{{ session('info') }}" type="info" heading="{{ __('Info') }}"></message>
    @endif
</div>

@if (session('view'))
    @include(session('view'))
@endif
