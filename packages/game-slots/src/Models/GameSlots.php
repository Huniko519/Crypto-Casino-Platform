<?php

namespace Packages\GameSlots\Models;

use App\Models\Game;
use Illuminate\Database\Eloquent\Model;

class GameSlots extends Model
{
    public function game()
    {
        return $this->morphOne(Game::class, 'gameable');
    }

    /**
     * Format $gameable->result attribute
     *
     * @return string
     */
    public function getResultAttribute()
    {
        $result = [];

        if ($this->free_games_count > 0) {
            $result[] = $this->free_games_count == 1 ?
                __(':n free spin', ['n' => $this->free_games_count]) :
                __(':n free spins', ['n' => $this->free_games_count]);
        }

        if ($this->scatters_count > 0) {
            // trans_choice() doesn't pick up translations for a some reason, so using a workaround
            // $result[] = trans_choice(':n scatter|:n scatters', $this->scatters_count, ['n' => $this->scatters_count]);
            $result[] = $this->scatters_count == 1 ?
                __(':n scatter', ['n' => $this->scatters_count]) :
                __(':n scatters', ['n' => $this->scatters_count]);
        }

        if ($this->lines_win > 0) {
            // trans_choice() doesn't pick up translations for a some reason, so using a workaround
            // $result[] = trans_choice(':n line|:n lines', $this->lines_win, ['n' => $this->lines_win]);
            $result[] = $this->lines_win == 1 ?
                __(':n line', ['n' => $this->lines_win]) :
                __(':n lines', ['n' => $this->lines_win]);
        }

        return !empty($result) ? implode(', ', $result) : __('Nothing');
    }
}
