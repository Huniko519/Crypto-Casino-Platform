<footer class="footer border-top border-primary">
    <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }}">
        <div class="row">
            <div class="col text-center text-lg-left">
                <span class="text-muted">&copy; {{ __('Crypto Casino') }} {{ __('v.') }}{{ config('app.version') }}</span>
            </div>
        </div>
    </div>
</footer>
