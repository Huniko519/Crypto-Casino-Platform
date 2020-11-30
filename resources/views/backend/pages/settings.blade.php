@extends('backend.layouts.main')

@section('title')
    {{ __('Settings') }}
@endsection

@section('content')
    <form method="POST" action="{{ route('backend.settings.update') }}">
        @csrf
        <div class="accordion">
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-general" aria-expanded="true">
                            {{ __('General') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-general" class="collapse">
                    <div class="card-body text-body">
                        <div class="form-group">
                            <label>{{ __('Color scheme') }}</label>
                            <select name="THEME" class="custom-select">
                                @foreach($schemes as $code => $scheme)
                                    <option value="{{ $code }}" {{ $code==config('settings.theme') ? 'selected' : '' }}>{{ $scheme }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Layout') }}</label>
                            <select name="LAYOUT" class="custom-select">
                                <option value="boxed" {{ config('settings.layout')=='boxed' ? 'selected' : '' }}>{{ __('Boxed') }}</option>
                                <option value="fluid" {{ config('settings.layout')=='fluid' ? 'selected' : '' }}>{{ __('Full-width') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Language') }}</label>
                            <select name="LOCALE" class="custom-select">
                                @foreach($locales as $code => $locale)
                                    <option value="{{ $code }}" {{ $code==config('app.locale') ? 'selected' : '' }}>{{ $locale->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-home" aria-expanded="true">
                            {{ __('Home page') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-home" class="collapse">
                    <div class="card-body text-body">
                        <div class="accordion">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-home-slider" aria-expanded="true">
                                            {{ __('Slider') }}
                                        </button>
                                    </h5>
                                </div>
                                <div id="tab-home-slider" class="collapse ml-3">
                                    <div
                                        id="slider-settings"
                                        data-props="{{ json_encode(['settings' => config('settings.home.slider')], JSON_NUMERIC_CHECK) }}"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-users" aria-expanded="true">
                            {{ __('Users') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-users" class="collapse">
                    <div class="card-body text-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="hidden" name="USERS_EMAIL_VERIFICATION" value="false">
                                <input type="checkbox" name="USERS_EMAIL_VERIFICATION" value="true" class="form-check-input" {{ config('settings.users.email_verification') ? 'checked="checked"' : '' }}>
                                <label class="form-check-label">
                                    {{ __('Require email verification') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Session lifetime') }}</label>
                            <select name="SESSION_LIFETIME" class="custom-select">
                                <option value="120" {{ config('session.lifetime')==120 ? 'selected' : '' }}>{{ __('2 hours') }}</option>
                                <option value="720" {{ config('session.lifetime')==720 ? 'selected' : '' }}>{{ __('12 hours') }}</option>
                                <option value="1440" {{ config('session.lifetime')==1440 ? 'selected' : '' }}>{{ __('24 hours') }}</option>
                                <option value="10080" {{ config('session.lifetime')==10080 ? 'selected' : '' }}>{{ __('1 week') }}</option>
                                <option value="10080" {{ config('session.lifetime')==10080 ? 'selected' : '' }}>{{ __('1 week') }}</option>
                                <option value="43200" {{ config('session.lifetime')==43200 ? 'selected' : '' }}>{{ __('1 month') }}</option>
                                <option value="525600" {{ config('session.lifetime')==525600 ? 'selected' : '' }}>{{ __('1 year') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-bonuses" aria-expanded="true">
                            {{ __('Bonuses') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-bonuses" class="collapse">
                    <div class="card-body text-body">
                        <div class="form-group">
                            <label>{{ __('User sign up bonus') }}</label>
                            <input type="text" name="BONUSES_SIGN_UP_CREDITS" class="form-control" value="{{ config('settings.bonuses.sign_up_credits') }}">
                            <small>{{ __('Number of credits given to all users on sign up.') }}</small>
                        </div>
                        <div class="form-row">
                            <label class="ml-1">{{ __('Game loss bonus') }}</label>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ __('When net loss') }} >=</span>
                                    </div>
                                    <input type="text" name="BONUSES_GAME_LOSS_AMOUNT_MIN" class="form-control" value="{{ config('settings.bonuses.game.loss_amount_min') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{ __('credits') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ __('Give back') }}</span>
                                    </div>
                                    <input type="text" name="BONUSES_GAME_LOSS_AMOUNT_PCT" class="form-control" value="{{ config('settings.bonuses.game.loss_amount_pct') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <label class="ml-1">{{ __('Game win bonus') }}</label>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ __('When net win') }} >=</span>
                                    </div>
                                    <input type="text" name="BONUSES_GAME_WIN_AMOUNT_MIN" class="form-control" value="{{ config('settings.bonuses.game.win_amount_min') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">{{ __('credits') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ __('Give back') }}</span>
                                    </div>
                                    <input type="text" name="BONUSES_GAME_WIN_AMOUNT_PCT" class="form-control" value="{{ config('settings.bonuses.game.win_amount_pct') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @installed('payments')
                            <div class="form-group">
                                <div class="form-row">
                                    <label class="ml-1">{{ __('Deposit bonus') }}</label>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ __('When deposit') }} >=</span>
                                            </div>
                                            <input type="text" name="BONUSES_DEPOSIT_AMOUNT_MIN" class="form-control" value="{{ config('settings.bonuses.deposit.amount_min') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ __('credits') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">{{ __('Give back') }}</span>
                                            </div>
                                            <input type="text" name="BONUSES_DEPOSIT_AMOUNT_PCT" class="form-control" value="{{ config('settings.bonuses.deposit.amount_pct') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="BONUSES_DEPOSIT_AMOUNT_MIN" value="0">
                            <input type="hidden" name="BONUSES_DEPOSIT_AMOUNT_PCT" value="0">
                        @endinstalled
                        <div class="form-group">
                            <label>{{ __('Referee sign up bonus') }}</label>
                            <div class="input-group">
                                <input type="text" name="BONUSES_REFERRAL_REFEREE_SIGN_UP_CREDITS" class="form-control" value="{{ config('settings.bonuses.referral.referee_sign_up_credits') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ __('credits') }}</span>
                                </div>
                            </div>
                            <small>{{ __('How much will the referred user get when signing up using a referral link.') }}</small>
                            <small>{{ __('This setting can be overridden on the user level.') }}</small>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Referrer sign up bonus') }}</label>
                            <div class="input-group">
                                <input type="text" name="BONUSES_REFERRAL_REFERRER_SIGN_UP_CREDITS" class="form-control" value="{{ config('settings.bonuses.referral.referrer_sign_up_credits') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ __('credits') }}</span>
                                </div>
                            </div>
                            <small>{{ __('How much will the referrer user get when anyone signs up using their referral link.') }}</small>
                            <small>{{ __('This setting can be overridden on the user level.') }}</small>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Referrer game loss bonus') }}</label>
                            <div class="input-group">
                                <input type="text" name="BONUSES_REFERRAL_REFERRER_GAME_LOSS_PCT" class="form-control" value="{{ config('settings.bonuses.referral.referrer_game_loss_pct') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <small>{{ __('How much (% of the net loss) will the referrer user get when a referred user loses a game.') }}</small>
                            <small>{{ __('This setting can be overridden on the user level.') }}</small>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Referrer game win bonus') }}</label>
                            <div class="input-group">
                                <input type="text" name="BONUSES_REFERRAL_REFERRER_GAME_WIN_PCT" class="form-control" value="{{ config('settings.bonuses.referral.referrer_game_win_pct') }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <small>{{ __('How much (% of the net win) will the referrer user get when a referred user wins a game.') }}</small>
                            <small>{{ __('This setting can be overridden on the user level.') }}</small>
                        </div>
                        @installed('raffle')
                            <div class="form-group">
                                <label>{{ __('Referrer raffle ticket purchase bonus') }}</label>
                                <div class="input-group">
                                    <input type="text" name="BONUSES_RAFFLE_TICKET_PCT" class="form-control" value="{{ config('settings.bonuses.raffle.ticket_pct') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <small>{{ __('How much (% of the ticket price) will the referrer user get when a referred user purchases a raffle ticket.') }}</small>
                            </div>
                        @else
                            <input type="hidden" name="BONUSES_RAFFLE_TICKET_PCT" value="0">
                        @endinstalled
                        @installed('payments')
                            <div class="form-group">
                                <label>{{ __('Referrer deposit bonus') }}</label>
                                <div class="input-group">
                                    <input type="text" name="BONUSES_REFERRAL_REFERRER_DEPOSIT_PCT" class="form-control" value="{{ config('settings.bonuses.referral.referrer_deposit_pct') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <small>{{ __('How much (% of the deposit amount) will the referrer user get when a referred user completes a deposit.') }}</small>
                                <small>{{ __('This setting can be overridden on the user level.') }}</small>
                            </div>
                        @else
                            <input type="hidden" name="BONUSES_REFERRAL_REFERRER_DEPOSIT_PCT" value="0">
                        @endinstalled
                    </div>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-bots" aria-expanded="true">
                            {{ __('Bots') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-bots" class="collapse">
                    <div class="card-body text-body">
                        <p class="ml-1">
                            {!! __('Bots can be created or deleted on the <a href=":url">Maintenance page</a>', ['url' => route('backend.maintenance.index')]) !!}
                        </p>
                        <div class="accordion">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-bots-games" aria-expanded="true">
                                            {{ __('Games') }}
                                        </button>
                                    </h5>
                                </div>
                                <div id="tab-bots-games" class="collapse ml-3">
                                    <div class="card-body">
                                        <p>
                                            {{ __('Periodically (depending on the frequency setting) a random number of bots will be selected (according to min and max bots settings).') }}
                                            {{ __('Then every selected bot will play exactly one game with random parameters.') }}
                                        </p>
                                        <div class="form-group">
                                            <label>{{ __('Frequency') }}</label>
                                            <select name="BOTS_PLAY_FREQUENCY" class="custom-select">
                                                <option value="1" {{ config('settings.bots.game.frequency')==1 ? 'selected' : '' }}>{{ __('Every minute') }}</option>
                                                <option value="5" {{ config('settings.bots.game.frequency')==5 ? 'selected' : '' }}>{{ __('Every 5 minutes') }}</option>
                                                <option value="10" {{ config('settings.bots.game.frequency')==10 ? 'selected' : '' }}>{{ __('Every 10 minutes') }}</option>
                                                <option value="15" {{ config('settings.bots.game.frequency')==15 ? 'selected' : '' }}>{{ __('Every 15 minutes') }}</option>
                                                <option value="30" {{ config('settings.bots.game.frequency')==30 ? 'selected' : '' }}>{{ __('Every 30 minutes') }}</option>
                                            </select>
                                            <small>{{ __('Choose how often bots will awake.') }}</small>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Min bots') }}</label>
                                            <input type="text" name="BOTS_SELECT_COUNT_MIN" class="form-control" value="{{ config('settings.bots.game.count_min') }}">
                                            <small>{{ __('Minimum number of bots to play a game during each cycle.') }}</small>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Max bots') }}</label>
                                            <input type="text" name="BOTS_SELECT_COUNT_MAX" class="form-control" value="{{ config('settings.bots.game.count_max') }}">
                                            <small>{{ __('Maximum number of bots to play a game during each cycle.') }}</small>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Min bet') }}</label>
                                            <div class="input-group">
                                                <input type="text" name="BOTS_MIN_BET" class="form-control" value="{{ config('settings.bots.game.min_bet') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ __('credits') }}</span>
                                                </div>
                                            </div>
                                            <small>
                                                {{ __('Minimum bet a bot is allowed to make.') }}
                                                {{ __('Leave empty to use the limit specified in the game settings.') }}
                                            </small>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Max bet') }}</label>
                                            <div class="input-group">
                                                <input type="text" name="BOTS_MAX_BET" class="form-control" value="{{ config('settings.bots.game.max_bet') }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ __('credits') }}</span>
                                                </div>
                                            </div>
                                            <small>
                                                {{ __('Maximum bet a bot is allowed to make.') }}
                                                {{ __('Leave empty to use the limit specified in the game settings.') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                @installed('raffle')
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-bots-raffles" aria-expanded="true">
                                                {{ __('Raffles') }}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="tab-bots-raffles" class="collapse ml-3">
                                        <div class="card-body">
                                            <p>
                                                {{ __('Periodically (depending on the frequency setting) a random number of bots will be selected (according to min and max bots settings).') }}
                                                {{ __('Then every selected bot will purchase a random number of raffle tickets.') }}
                                            </p>
                                            <div class="form-group">
                                                <label>{{ __('Frequency') }}</label>
                                                <select name="BOTS_RAFFLE_FREQUENCY" class="custom-select">
                                                    <option value="1" {{ config('settings.bots.raffle.frequency')==1 ? 'selected' : '' }}>{{ __('Every minute') }}</option>
                                                    <option value="5" {{ config('settings.bots.raffle.frequency')==5 ? 'selected' : '' }}>{{ __('Every 5 minutes') }}</option>
                                                    <option value="10" {{ config('settings.bots.raffle.frequency')==10 ? 'selected' : '' }}>{{ __('Every 10 minutes') }}</option>
                                                    <option value="15" {{ config('settings.bots.raffle.frequency')==15 ? 'selected' : '' }}>{{ __('Every 15 minutes') }}</option>
                                                    <option value="30" {{ config('settings.bots.raffle.frequency')==30 ? 'selected' : '' }}>{{ __('Every 30 minutes') }}</option>
                                                </select>
                                                <small>{{ __('Choose how often bots will awake.') }}</small>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Min bots') }}</label>
                                                <input type="text" name="BOTS_RAFFLE_COUNT_MIN" class="form-control" value="{{ config('settings.bots.raffle.count_min') }}">
                                                <small>{{ __('Minimum number of bots to purchase raffle tickets.') }}</small>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Max bots') }}</label>
                                                <input type="text" name="BOTS_RAFFLE_COUNT_MAX" class="form-control" value="{{ config('settings.bots.raffle.count_max') }}">
                                                <small>{{ __('Maximum number of bots to purchase raffle tickets.') }}</small>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Min tickets') }}</label>
                                                <input type="text" name="BOTS_RAFFLE_TICKETS_MIN" class="form-control" value="{{ config('settings.bots.raffle.tickets_min') }}">
                                                <small>{{ __('Minimum number of tickets to purchase during each cycle.') }}</small>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Max tickets') }}</label>
                                                <input type="text" name="BOTS_RAFFLE_TICKETS_MAX" class="form-control" value="{{ config('settings.bots.raffle.tickets_max') }}">
                                                <small>{{ __('Maximum number of tickets to purchase during each cycle.') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endinstalled
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-numbers" aria-expanded="true">
                            {{ __('Number formatting') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-numbers" class="collapse">
                    <div class="card-body text-body">
                        <div class="form-group">
                            <label>{{ __('Decimal point') }}</label>
                            <select name="FORMAT_NUMBER_DECIMAL_POINT" class="custom-select">
                                @foreach($separators as $code => $separator)
                                    <option value="{{ $code }}" {{ $code==config('settings.format.number.decimal_point') ? 'selected' : '' }}>{{ $separator }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Thousands separator') }}</label>
                            <select name="FORMAT_NUMBER_THOUSANDS_SEPARATOR" class="custom-select">
                                @foreach($separators as $code => $separator)
                                    <option value="{{ $code }}" {{ $code==config('settings.format.number.thousands_separator') ? 'selected' : '' }}>{{ $separator }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-mail" aria-expanded="true">
                            {{ __('Mail') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-mail" class="collapse">
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ __('Mail driver') }}</label>
                            <select name="MAIL_DRIVER" class="custom-select">
                                <option value="sendmail" {{ config('mail.driver')=='sendmail' ? 'selected' : '' }}>{{ __('SendMail') }}</option>
                                <option value="smtp" {{ config('mail.driver')=='smtp' ? 'selected' : '' }}>{{ __('SMTP') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ __('SMTP host') }}</label>
                            <input type="text" name="MAIL_HOST" class="form-control" value="{{ config('mail.host') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('SMTP port') }}</label>
                            <input type="text" name="MAIL_PORT" class="form-control" value="{{ config('mail.port') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('SMTP email from address') }}</label>
                            <input type="text" name="MAIL_FROM_ADDRESS" class="form-control" value="{{ config('mail.from.address') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('SMTP email from name') }}</label>
                            <input type="text" name="MAIL_FROM_NAME" class="form-control" value="{{ config('mail.from.name') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('SMTP user') }}</label>
                            <input type="text" name="MAIL_USERNAME" class="form-control" value="{{ config('mail.username') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('SMTP password') }}</label>
                            <input type="password" name="MAIL_PASSWORD" class="form-control" value="{{ config('mail.password') }}">
                        </div>
                        <div class="form-group">
                            <label>{{ __('Mail encryption') }}</label>
                            <select name="MAIL_ENCRYPTION" class="custom-select">
                                <option value="" {{ !config('mail.encryption') ? 'selected' : '' }}>{{ __('None') }}</option>
                                <option value="tls" {{ config('mail.encryption')=='tls' ? 'selected' : '' }}>{{ __('TLS') }}</option>
                                <option value="ssl" {{ config('mail.encryption')=='ssl' ? 'selected' : '' }}>{{ __('SSL') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-integration" aria-expanded="true">
                            {{ __('Integration') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-integration" class="collapse">
                    <div class="card-body">
                        <div class="accordion">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-integration-gtm" aria-expanded="true">
                                            {{ __('Google Tag Manager') }}
                                        </button>
                                    </h5>
                                </div>
                                <div id="tab-integration-gtm" class="collapse ml-3">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>{{ __('Container ID') }}</label>
                                            <input type="text" name="GTM_CONTAINER_ID" class="form-control" value="{{ config('settings.gtm_container_id') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-integration-recaptcha" aria-expanded="true">
                                            {{ __('Google reCaptcha') }}
                                        </button>
                                    </h5>
                                </div>
                                <div id="tab-integration-recaptcha" class="collapse ml-3">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>{{ __('Public key') }}</label>
                                            <input type="text" name="RECAPTCHA_PUBLIC_KEY" class="form-control" value="{{ config('settings.recaptcha.public_key') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Private key') }}</label>
                                            <input type="text" name="RECAPTCHA_SECRET_KEY" class="form-control" value="{{ config('settings.recaptcha.secret_key') }}">
                                            <small>
                                                {{ __('Leave empty if you do not want to use reCaptcha validation. Public and private keys can be obtained at :url', ['url' => 'https://www.google.com/recaptcha']) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-integration-pusher" aria-expanded="true">
                                            {{ __('Pusher') }}
                                        </button>
                                    </h5>
                                </div>
                                <div id="tab-integration-pusher" class="collapse ml-3">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input type="hidden" name="BROADCAST_DRIVER" value="pusher">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('App ID') }}</label>
                                            <input type="text" name="PUSHER_APP_ID" class="form-control" value="{{ config('broadcasting.connections.pusher.app_id') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('App key') }}</label>
                                            <input type="text" name="PUSHER_APP_KEY" class="form-control" value="{{ config('broadcasting.connections.pusher.key') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('App secret') }}</label>
                                            <input type="text" name="PUSHER_APP_SECRET" class="form-control" value="{{ config('broadcasting.connections.pusher.secret') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('Cluster') }}</label>
                                            <input type="text" name="PUSHER_APP_CLUSTER" class="form-control" value="{{ config('broadcasting.connections.pusher.options.cluster') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach(array_keys(config('services.login_providers')) as $provider)
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-integration-{{ $provider }}" aria-expanded="true">
                                                {{ ucfirst($provider) }}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="tab-integration-{{ $provider }}" class="collapse ml-3">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>{{ __('Client ID') }}</label>
                                                <input type="text" name="{{ strtoupper($provider) }}_CLIENT_ID" value="{{ config('services.'.$provider.'.client_id') }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Client secret') }}</label>
                                                <input type="text" name="{{ strtoupper($provider) }}_CLIENT_SECRET" value="{{ config('services.'.$provider.'.client_secret') }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Redirect URL') }}</label>
                                                <input type="text" value="{{ url(config('services.'.$provider.'.redirect')) }}" class="form-control" disabled="disabled">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card border-primary">
                <div class="card-header border-primary">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-developer" aria-expanded="true">
                            {{ __('Developer') }}
                        </button>
                    </h5>
                </div>
                <div id="tab-developer" class="collapse">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="hidden" name="APP_DEBUG" value="false">
                                <input type="checkbox" name="APP_DEBUG" value="true" class="form-check-input" {{ config('app.debug') ? 'checked="checked"' : '' }}>
                                <label class="form-check-label">
                                    {{ __('Debug mode') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Log level') }}</label>
                            <select name="APP_LOG_LEVEL" class="custom-select">
                                @foreach($log_levels as $log_level)
                                    <option value="{{ $log_level }}" {{ $log_level==env('APP_LOG_LEVEL', 'emergency') ? 'selected' : '' }}>{{ __(ucfirst($log_level)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            @packageview('backend.pages.settings')
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ mix('js/pages/admin/settings.js') }}"></script>
@endpush
