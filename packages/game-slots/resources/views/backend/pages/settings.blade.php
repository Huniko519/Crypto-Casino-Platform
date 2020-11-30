<div class="card border-primary">
    <div class="card-header border-primary">
        <h5 class="mb-0">
            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-game-slots" aria-expanded="true">
                {{ __('Game: :game', ['game' => __('Slots')]) }}
            </button>
        </h5>
    </div>
    <div id="tab-game-slots" class="collapse">
        <div class="card-body">
            <div class="accordion">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-game-slots-options" aria-expanded="true">
                                {{ __('General') }}
                            </button>
                        </h5>
                    </div>
                    <div id="tab-game-slots-options" class="collapse ml-3">
                        <div class="card-body">
                            <div class="form-group">
                                <label>{{ __('Categories') }}</label>
                                <input type="text" name="GAME_SLOTS_CATEGORIES" class="form-control" value="{{ config('game-slots.categories') }}">
                                <small>{{ __('Comma-delimited list') }}</small>
                            </div>
                            <file-upload
                                label="{{ __('Banner') }}"
                                path="{{ config('game-slots.banner') }}"
                                name="GAME_SLOTS_BANNER"
                                file-name="slots"
                                folder="home"
                            ></file-upload>
                            <file-upload
                                label="{{ __('Background image') }}"
                                path="{{ config('game-slots.background') }}"
                                name="GAME_SLOTS_BACKGROUND"
                                file-name="background"
                                folder="games/slots"
                                :can-clear="true"
                            ></file-upload>
                            <div class="form-group">
                                <label>{{ __('Min bet') }}</label>
                                <input type="text" name="GAME_SLOTS_MIN_BET" class="form-control" value="{{ config('game-slots.min_bet') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Max bet') }}</label>
                                <input type="text" name="GAME_SLOTS_MAX_BET" class="form-control" value="{{ config('game-slots.max_bet') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Bet increment / decrement amount') }}</label>
                                <input type="number" name="GAME_SLOTS_BET_CHANGE_AMOUNT" class="form-control" value="{{ config('game-slots.bet_change_amount') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Default bet') }}</label>
                                <input type="text" name="GAME_SLOTS_DEFAULT_BET" class="form-control" value="{{ config('game-slots.default_bet') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ __('Default lines count') }}</label>
                                <input type="text" name="GAME_SLOTS_DEFAULT_LINES" class="form-control" value="{{ config('game-slots.default_lines') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-game-slots-symbols" aria-expanded="true">
                                {{ __('Symbols') }}
                            </button>
                        </h5>
                    </div>
                    <div id="tab-game-slots-symbols" class="collapse ml-3">
                        <div class="card-body">
                            <div class="form-group">

                                <div id="game_slots_symbols" data-url="{{ route('backend.games.slots.files') }}" data-token="{{ csrf_token() }}" data-storage="{{ asset('storage') . '/games/slots/' }}" class="slots-symbols">
                                    <input id="game_slots_symbols_input" type="hidden" name="GAME_SLOTS_SYMBOLS" value="{{ json_encode(config('game-slots.symbols'),JSON_FORCE_OBJECT) }}">
                                    <div id="game_slots_symbols_items" class="items"></div>
                                    <div id="game_slots_symbols_place" class="place-area">
                                        <i class="fa fa-spinner fa-spin"></i>
                                        <i class="fa fa-times-circle"></i>
                                        <input type="file" multiple>
                                        <div class="error text">{{ __('Only png can be used') }}</div>
                                        {{ __('Drag and drop or upload a symbol image here') }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#tab-game-slots-reels" aria-expanded="true">
                                {{ __('Reels') }}
                            </button>
                        </h5>
                    </div>
                    <div id="tab-game-slots-reels" class="collapse ml-3">
                        <div class="card-body">
                            <div class="form-group">
                                <p>{{ __('Drag and drop availale symbols on the reels. You can also adjust the order of each symbol in the reel if necessary.') }}</p>
                                <input id="game_slots_reel_input" type="hidden" name="GAME_SLOTS_REELS" value="{{ json_encode(config('game-slots.reels'),JSON_FORCE_OBJECT) }}">
                                <div id="game_slots_reel_symbols" class="reel-symbols"></div>
                                <div id="game_slots_reels" class="reels">
                                    <div class="reel" data-idx="0"></div>
                                    <div class="reel" data-idx="1"></div>
                                    <div class="reel" data-idx="2"></div>
                                    <div class="reel" data-idx="3"></div>
                                    <div class="reel" data-idx="4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ mix('css/games/slots/' . $settings->theme . '.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('js/games/slots/admin.js') }}"></script>
@endpush
