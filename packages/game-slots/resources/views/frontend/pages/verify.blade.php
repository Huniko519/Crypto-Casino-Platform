<div class="form-group">
    <label>{{ __('Adjusted reels positions') }}</label>
    <input type="text" class="form-control text-muted" value="{{ $game->gameable->reel_positions }}" readonly>
    <small>
        {{ __('Each reel is spinned N extra times, where N corresponds to a digit in the Shift value at the i-th position.') }}
    </small>
</div>