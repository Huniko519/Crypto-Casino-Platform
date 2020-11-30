<?php

namespace Packages\GameSlots\Services;

use App\Services\GameService;
use App\Helpers\Games\Slot;
use Illuminate\Database\Eloquent\Model;
use Packages\GameSlots\Models\GameFreeSlots;
use Packages\GameSlots\Models\GameSlots;

class GameSlotsService extends GameService
{
    protected $gameableModel = GameSlots::class;

    private $slot;

    const lines = [
        [1, 1, 1, 1, 1],
        [0, 0, 0, 0, 0],
        [2, 2, 2, 2, 2],

        [1, 1, 0, 1, 2],
        [1, 1, 2, 1, 0],
        [1, 0, 1, 2, 1],
        [1, 0, 1, 2, 2],
        [1, 0, 0, 1, 2],
        [1, 2, 1, 0, 1],
        [1, 2, 2, 1, 0],
        [1, 2, 1, 0, 0],

        [0, 1, 2, 1, 0],
        [0, 1, 1, 1, 2],
        [0, 0, 1, 2, 2],
        [0, 0, 1, 2, 1],
        [0, 0, 0, 1, 2],

        [2, 1, 0, 1, 2],
        [2, 1, 1, 1, 0],
        [2, 2, 1, 0, 0],
        [2, 2, 1, 0, 1]
    ];

    public function __construct($user = NULL)
    {
        parent::__construct($user);

        $this->slot = new Slot(array_map(function($v) {
            return count($v);
        }, config('game-slots.reels')));
    }

    protected function createGameable(): Model
    {
        $gameable = new GameSlots();
        $gameable->lines_bet = 0;
        $gameable->bet_amount = 0;
        $gameable->lines_win = 0;
        $gameable->scatters_count = 0;
        $gameable->free_games_count = 0;
        $gameable->reel_positions = implode(',', $this->slot->getReelsPositions());
        $gameable->save();

        return $gameable;
    }

    protected function makeSecret(): string
    {
        return implode(',', $this->slot->spin()->getReelsPositions()); // convert reels positions to comma-delimited string
    }

    protected function adjustSecret(): string
    {
        $shiftNumberAsString = (string) $this->game->shift_value;

        // each reel is additionally spinned N times
        $shifts = array_map(function ($i) use ($shiftNumberAsString) {
            return $shiftNumberAsString[$i] ?? 0;
        }, array_keys($this->slot->getReels()));

        return implode(',', $this->slot->shift($shifts)->getReelsPositions());
    }

    /**
     * Play game
     *
     * @return mixed
     */
    public function play($params): GameService
    {
        if (!$this->game->gameable)
            throw new \Exception('Gameable model should be loaded first.');

        // set initial reels positions
        $this->slot->setReelsPositions(explode(',', $this->game->secret));

        $syms = config('game-slots.symbols');
        $reels = config('game-slots.reels');

        $freeGames = GameFreeSlots::where('account_id', $this->game->account->id)->where('quantity', '>', 0)->first();

        $this->game->gameable->lines_bet      = $freeGames->lines ?? $params['lines_count'];
        $this->game->gameable->bet_amount     = $freeGames->bet ?? $params['bet'];
        $this->game->gameable->reel_positions = $this->adjustSecret();

        $win = 0;
        $win_scatters_ttl = 0;
        $win_free_games_ttl = 0;
        $win_scatters = [[], [], [], [], []];
        $win_free_games = [[], [], [], [], []];
        $win_lines_ttl = [];
        $win_lines = [];

        $get_reel_position = function ($reel, $idx) use ($reels, &$get_reel_position) {
            if ($idx < 0) {
                return $get_reel_position($reel, count($reels[$reel]) + $idx);
            } else if ($idx >= count($reels[$reel])) {
                return $get_reel_position($reel, $idx - count($reels[$reel]));
            }
            return $idx;
        };

        $reel_positions = explode(',', $this->game->gameable->reel_positions);

        for ($reel = 0; $reel < 5; $reel++) {
            for ($step = 0; $step < 3; $step++) {
                if ($syms[$reels[$reel][$get_reel_position($reel, $reel_positions[$reel] + $step)]]['scatter']) {
                    $this->game->gameable->scatters_count++;
                    $win_scatters[$reel][] = $get_reel_position($reel, $reel_positions[$reel] + $step);
                }
            }
        }

        if ($this->game->gameable->scatters_count) {
            $idx_free = -1;
            foreach ($syms as $idx => $sym) {
                if ($sym['scatter']) {
                    $idx_free = $idx;
                    break;
                }
            }

            if ($syms[$idx_free]['w' . ($this->game->gameable->scatters_count > 5 ? 5 : $this->game->gameable->scatters_count) . 't'] == 'x')
                $win_scatters_ttl = $this->game->gameable->bet_amount * $this->game->gameable->lines_bet * $syms[$idx_free]['w' . ($this->game->gameable->scatters_count > 5 ? 5 : $this->game->gameable->scatters_count)];
            else
                $win_scatters_ttl = $syms[$idx_free]['w' . ($this->game->gameable->scatters_count > 5 ? 5 : $this->game->gameable->scatters_count)];

            $win += $win_scatters_ttl;

            if (!$win_scatters_ttl) {
                $this->game->gameable->scatters_count = 0;
                $win_scatters = [];
            }
        }

        $freeSymbolsCount = 0;

        for ($reel = 0; $reel < 5; $reel++) {
            for ($step = 0; $step < 3; $step++) {
                if ($syms[$reels[$reel][$get_reel_position($reel, $reel_positions[$reel] + $step)]]['free'] ?? FALSE) {
                    $freeSymbolsCount++;
                    $win_free_games[$reel][] = $get_reel_position($reel, $reel_positions[$reel] + $step);
                }
            }
        }

        if ($freeSymbolsCount) {
            $idx_free = -1;
            foreach ($syms as $idx => $sym) {
                if (isset($sym['free']) && $sym['free']) {
                    $idx_free = $idx;
                    break;
                }
            }

            $win_free_games_ttl = (int) $syms[$idx_free]['w' . $freeSymbolsCount] ?? 0;

            // if there are free spins symbols, but not enough to earn free spins
            if (!$win_free_games_ttl) {
                $win_free_games = [];
            }
        }

        foreach (GameSlotsService::lines as $line_idx => $line) {
            if ($line_idx >= $this->game->gameable->lines_bet) break;
            // if($line_idx!=7)continue;
            $sym_idx = $reels[0][$get_reel_position(0, $reel_positions[0] + $line[0])];
            $sym_cnt = 1;
            $wild_cnt = $syms[$sym_idx]['wild'] ? 1 : 0;
            $wild_line = $wild_cnt;
            $win_line = 0;
            $win_pos = [];
            $wild_idx = $syms[$sym_idx]['wild'] ? $sym_idx : -1;
            for ($reel = 1; $reel < 6; $reel++) {
                if ($reel == 5)
                    $sym_idx_new = -1;
                else
                    $sym_idx_new = $reels[$reel][$get_reel_position($reel, $reel_positions[$reel] + $line[$reel])];

                if ($sym_idx_new != -1 && $wild_cnt > 0 && !$syms[$sym_idx_new]['wild']) {
                    $win_line_new = 0;
                    $win_pos_new = [];
                    if ($syms[$wild_idx]['w' . $wild_cnt . 't'] == 'x')
                        $win_line_new = $this->game->gameable->bet_amount * $syms[$wild_idx]['w' . $wild_cnt];
                    else
                        $win_line_new = $syms[$wild_idx]['w' . $wild_cnt];
                    if ($win_line_new > $win_line) {
                        $win_line = $win_line_new;
                        $wild_cnt_i = $wild_cnt;
                        while ($wild_cnt_i > 0) {
                            $win_pos_new[$reel - $wild_cnt_i] = $get_reel_position($reel - $wild_cnt_i, $reel_positions[$reel - $wild_cnt_i] + $line[$reel - $wild_cnt_i]);
                            $wild_cnt_i--;
                        }
                        $win_pos = $win_pos_new;
                    }
                }

                if ($sym_idx_new != -1 && $syms[$sym_idx_new]['wild']) {
                    $wild_cnt++;
                    $wild_idx = $sym_idx_new;
                }

                if ($sym_idx_new != -1 && $syms[$sym_idx]['wild'] && $sym_idx_new != $sym_idx) {
                    $sym_idx = $sym_idx_new;
                    $sym_cnt = $wild_cnt;
                    $wild_line = $wild_cnt;
                }
                if (
                    $sym_idx_new != -1 &&
                    ($sym_idx_new == $sym_idx || $syms[$sym_idx_new]['wild']) &&
                    !$syms[$sym_idx]['scatter'] &&
                    !($syms[$sym_idx]['free'] ?? FALSE)
                )
                    $sym_cnt++;
                else {
                    if ($syms[$sym_idx]['wild'])
                        $sym_cnt = 1 + $wild_cnt;
                    elseif ($syms[$sym_idx]['w' . $sym_cnt] && !$syms[$sym_idx]['scatter'] && !($syms[$sym_idx]['free'] ?? FALSE)) {
                        $win_line_new = 0;
                        $win_pos_new = [];
                        if ($syms[$sym_idx]['w' . $sym_cnt . 't'] == 'x')
                            $win_line_new = $this->game->gameable->bet_amount * $syms[$sym_idx]['w' . $sym_cnt];
                        else
                            $win_line_new = $syms[$sym_idx]['w' . $sym_cnt];
                        if ($win_line_new > $win_line) {
                            $win_line = $win_line_new;
                            while ($sym_cnt > 0) {
                                $win_pos_new[$reel - $sym_cnt] = $get_reel_position($reel - $sym_cnt, $reel_positions[$reel - $sym_cnt] + $line[$reel - $sym_cnt]);
                                $sym_cnt--;
                            }
                            $win_pos = $win_pos_new;
                        }
                        $sym_cnt = 1 + $wild_cnt;
                    } elseif ($syms[$sym_idx]['scatter'] || ($syms[$sym_idx]['free'] ?? FALSE))
                        $sym_cnt = 1;
                    else
                        $sym_cnt = 1 + $wild_cnt;
                    break;
                    // $sym_idx = $sym_idx_new;
                }

                if ($sym_idx_new != -1 && !$syms[$sym_idx_new]['wild'])
                    $wild_cnt = 0;
            }

            $win += $win_line;
            if ($win_line) {
                for ($i = 0; $i < 5; $i++)
                    if (!isset($win_pos[$i]))
                        $win_pos[$i] = null;
                $this->game->gameable->lines_win++;
                $win_lines[$line_idx] = $win_pos;
                $win_lines_ttl[$line_idx] = $win_line;
            }
        }

        $this->game->gameable->free_games_count = $win_free_games_ttl;

        $free_games_quantity = $freeGames && $freeGames->quantity > 0
            ? $freeGames->quantity + $win_free_games_ttl - 1
            : $win_free_games_ttl;

        // user plays a free game
        if ($freeGames && $freeGames->quantity > 0) {
            $this->completeFreeGame($this->game->gameable->bet_amount * $this->game->gameable->lines_bet, $win);
        // users doesn't have free spins
        } else {
            $this->complete($this->game->gameable->bet_amount * $this->game->gameable->lines_bet, $win);
        }

        // if player uses a free spin or wins one
        if ($freeGames && $freeGames->quantity > 0 || $win_free_games_ttl > 0) {
            GameFreeSlots::updateOrCreate(
                [
                    'account_id' => $this->game->account->id
                ],
                [
                    'lines'     => $this->game->gameable->lines_bet,
                    'bet'       => $this->game->gameable->bet_amount,
                    'quantity'  => $free_games_quantity
                ]
            );
        }

        $this->game->gameable->win_scatters_ttl = $win_scatters_ttl;
        $this->game->gameable->win_scatters = $win_scatters;
        $this->game->gameable->free_games_quantity = $free_games_quantity;
        $this->game->gameable->win_free_games = $win_free_games;
        $this->game->gameable->is_free_game = $freeGames && $freeGames->quantity > 0;
        $this->game->gameable->win_lines_ttl = $win_lines_ttl;
        $this->game->gameable->win_lines = $win_lines;

        return $this;
    }
}
