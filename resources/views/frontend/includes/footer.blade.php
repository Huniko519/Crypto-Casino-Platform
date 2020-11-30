<footer class="footer border-top border-primary">
    <div class="{{ config('settings.layout') == 'boxed' ? 'container' : 'container-fluid' }}">
        <div class="row">
            <div class="col-12 col-lg-6 text-center text-lg-left">
                <span class="text-muted">
                    &copy;
                    <a href="{{ route('frontend.index') }}" class="text-muted">{{ __('Crypto Casino') }}</a>
                    {{ __('v.') }}{{ config('app.version') }}
                </span>
            </div>
            <div class="col-12 col-lg-6 text-center text-lg-right">
                <i class="fas fa-shield-alt text-muted"></i>
                <a href="{{ url('page/provably-fair') }}" class="text-muted">{{ __('Provably fair') }}</a>
                <span class="text-muted ml-2 mr-2">|</span>
                <a href="{{ url('page/privacy-policy') }}" class="text-muted">{{ __('Privacy policy') }}</a>
                <span class="text-muted ml-2 mr-2">|</span>
                <a href="{{ url('page/terms-of-use') }}" class="text-muted">{{ __('Terms of use') }}</a>
            </div>
        </div>
    </div>
</footer>
