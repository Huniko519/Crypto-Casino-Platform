@extends('backend.layouts.main')

@section('title')
    {{ $user->name }} :: {{ __('Edit') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('backend.users.update', array_merge(['user' => $user], request()->query())) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('Email') }}</label>
            <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('Role') }}</label>
            <select name="role" class="custom-select {{ $errors->has('role') ? 'is-invalid' : '' }}">
                <option value="{{ App\Models\User::ROLE_BOT }}" {{ $user->role == App\Models\User::ROLE_BOT ? 'selected' : '' }}>{{ __('app.user_role_'.App\Models\User::ROLE_BOT) }}</option>
                <option value="{{ App\Models\User::ROLE_USER }}" {{ $user->role == App\Models\User::ROLE_USER ? 'selected' : '' }}>{{ __('app.user_role_'.App\Models\User::ROLE_USER) }}</option>
                <option value="{{ App\Models\User::ROLE_ADMIN }}" {{ $user->role == App\Models\User::ROLE_ADMIN ? 'selected' : '' }}>{{ __('app.user_role_'.App\Models\User::ROLE_ADMIN) }}</option>
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Status') }}</label>
            <select name="status" class="custom-select {{ $errors->has('status') ? 'is-invalid' : '' }}">
                <option value="{{ App\Models\User::STATUS_ACTIVE }}" {{ $user->status == App\Models\User::STATUS_ACTIVE ? 'selected' : '' }}>{{ __('app.user_status_'.App\Models\User::STATUS_ACTIVE) }}</option>
                <option value="{{ App\Models\User::STATUS_BLOCKED }}" {{ $user->status == App\Models\User::STATUS_BLOCKED ? 'selected' : '' }}>{{ __('app.user_status_'.App\Models\User::STATUS_BLOCKED) }}</option>
            </select>
        </div>

        <div class="form-group">
            <label>{{ __('Password') }}</label>
            <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="{{ __('Password') }}">
            <small class="form-text text-muted">{{ __('Leave empty to preserve current user password.') }}</small>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" {{ $user->totp_secret ? 'checked="checked"' : '' }} disabled>
                <label class="form-check-label">
                    {{ __('2FA enabled') }}
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="form-check">
                <input id="individual-referral-bonuses" type="checkbox" name="individual_referral_bonuses" class="form-check-input" {{ old('individual_referral_bonuses', $user->has_individual_referral_bonuses) ? 'checked="checked"' : '' }}>
                <label class="form-check-label" for="individual-referral-bonuses">
                    {{ __('Override site-wide referral bonuses') }}
                </label>
            </div>
        </div>

        <div class="form-group referral-bonus-field {{ old('individual_referral_bonuses', $user->has_individual_referral_bonuses) ? '' : 'hidden' }}">
            <label>{{ __('Referee sign up bonus') }}</label>
            <div class="input-group">
                <input type="text" name="referee_sign_up_credits" class="form-control {{ $errors->has('referee_sign_up_credits') ? 'is-invalid' : '' }}" placeholder="{{ config('settings.bonuses.referral.referee_sign_up_credits') }}" value="{{ old('referee_sign_up_credits', $user->referee_sign_up_credits) }}">
                <div class="input-group-append">
                    <span class="input-group-text">{{ __('credits') }}</span>
                </div>
            </div>
        </div>

        <div class="form-group referral-bonus-field {{ old('individual_referral_bonuses', $user->has_individual_referral_bonuses) ? '' : 'hidden' }}">
            <label>{{ __('Referrer sign up bonus') }}</label>
            <div class="input-group">
                <input type="text" name="referrer_sign_up_credits" class="form-control {{ $errors->has('referrer_sign_up_credits') ? 'is-invalid' : '' }}" placeholder="{{ config('settings.bonuses.referral.referrer_sign_up_credits') }}" value="{{ old('referrer_sign_up_credits', $user->referrer_sign_up_credits) }}">
                <div class="input-group-append">
                    <span class="input-group-text">{{ __('credits') }}</span>
                </div>
            </div>
        </div>

        <div class="form-group referral-bonus-field {{ old('individual_referral_bonuses', $user->has_individual_referral_bonuses) ? '' : 'hidden' }}">
            <label>{{ __('Referrer game loss bonus') }}</label>
            <div class="input-group">
                <input type="text" name="referrer_game_loss_pct" class="form-control {{ $errors->has('referrer_game_loss_pct') ? 'is-invalid' : '' }}" placeholder="{{ config('settings.bonuses.referral.referrer_game_loss_pct') }}" value="{{ old('referrer_game_loss_pct', $user->referrer_game_loss_pct) }}">
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
        </div>

        <div class="form-group referral-bonus-field {{ old('individual_referral_bonuses', $user->has_individual_referral_bonuses) ? '' : 'hidden' }}">
            <label>{{ __('Referrer game win bonus') }}</label>
            <div class="input-group">
                <input type="text" name="referrer_game_win_pct" class="form-control {{ $errors->has('referrer_game_win_pct') ? 'is-invalid' : '' }}" placeholder="{{ config('settings.bonuses.referral.referrer_game_win_pct') }}" value="{{ old('referrer_game_win_pct', $user->referrer_game_win_pct) }}">
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
        </div>

        <div class="form-group referral-bonus-field {{ old('individual_referral_bonuses', $user->has_individual_referral_bonuses) ? '' : 'hidden' }}">
            <label>{{ __('Referrer deposit bonus') }}</label>
            <div class="input-group">
                <input type="text" name="referrer_deposit_pct" class="form-control {{ $errors->has('referrer_deposit_pct') ? 'is-invalid' : '' }}" placeholder="{{ config('settings.bonuses.referral.referrer_deposit_pct') }}" value="{{ old('referrer_deposit_pct', $user->referrer_deposit_pct) }}">
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>{{ __('Referred by') }}</label>
            <input class="form-control text-muted" value="{{ $user->referrer_id ? $user->referrer->name . ' (' . $user->referrer->email . ')' : '' }}" readonly>
        </div>

        <div class="form-group">
            <label>{{ __('Last login at') }}</label>
            <input class="form-control text-muted" value="{{ $user->last_login_at }} ({{ $user->last_login_at->diffForHumans() }})" readonly>
        </div>

        <div class="form-group">
            <label>{{ __('Last login from') }}</label>
            <input class="form-control text-muted" value="{{ $user->last_login_from }}" readonly>
        </div>

        <div class="form-group">
            <label>{{ __('Created at') }}</label>
            <input class="form-control text-muted" value="{{ $user->created_at }} ({{ $user->created_at->diffForHumans() }})" readonly>
        </div>

        <div class="form-group">
            <label>{{ __('Updated at') }}</label>
            <input class="form-control text-muted" value="{{ $user->updated_at }} ({{ $user->updated_at->diffForHumans() }})" readonly>
        </div>

        <div class="form-group">
            <label>{{ __('Email verified at') }}</label>
            <input class="form-control text-muted" value="{{ $user->email_verified_at ? $user->email_verified_at . ' (' . $user->email_verified_at->diffForHumans() . ')' : __('never') }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            {{ __('Save') }}
        </button>
        <a href="{{ route('backend.users.delete', $user) }}" class="btn btn-danger float-right">
            <i class="far fa-trash-alt"></i>
            {{ __('Delete') }}
        </a>
    </form>
    <div class="mt-3">
        <a href="{{ route('backend.users.index', request()->query()) }}"><i class="fas fa-long-arrow-alt-left"></i> {{ __('Back to all users') }}</a>
    </div>
@endsection
@push('scripts')
<script>
    var checkbox = document.getElementById('individual-referral-bonuses');
    var fields = document.querySelectorAll('.referral-bonus-field');

    checkbox.addEventListener('change', function() {
        showHideFields(fields, this.checked);
    });

    function showHideFields(fields, display) {
        for (var i = 0; i < fields.length; ++i) {
            fields[i].style.display = display ? 'block' : 'none';
        }
    }
</script>
@endpush
