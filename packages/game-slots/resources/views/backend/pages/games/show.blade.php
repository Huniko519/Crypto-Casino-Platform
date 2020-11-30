<div class="form-group">
    <label>{{ __('Lines') }}</label>
    <input class="form-control text-muted" value="{{ $game->gameable->lines_bet }}" readonly>
</div>
<div class="form-group">
    <label>{{ __('Bet amount') }}</label>
    <input class="form-control text-muted" value="{{ $game->gameable->bet_amount }}" readonly>
</div>
<div class="form-group">
    <label>{{ __('Lines win') }}</label>
    <input class="form-control text-muted" value="{{ $game->gameable->lines_win }}" readonly>
</div>
<div class="form-group">
    <label>{{ __('Scatters win') }}</label>
    <input class="form-control text-muted" value="{{ $game->gameable->scatters_count }}" readonly>
</div>
<div class="form-group">
    <label>{{ __('Free spins') }}</label>
    <input class="form-control text-muted" value="{{ $game->gameable->free_games_count }}" readonly>
</div>
<div class="form-group">
    <label>{{ __('Reels positions') }}</label>
    <input class="form-control text-muted" value="{{ $game->gameable->reel_positions }}" readonly>
</div>
