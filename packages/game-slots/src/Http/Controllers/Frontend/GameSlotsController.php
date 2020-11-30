<?php

namespace Packages\GameSlots\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Packages\GameSlots\Http\Requests\Frontend\PlayGame;
use Packages\GameSlots\Models\GameFreeSlots;
use Packages\GameSlots\Services\GameSlotsService;

class GameSlotsController extends Controller
{
    public function show(Request $request, GameSlotsService $gameSlotsService)
    {
        $syms = [];
        $paytable = [];
        $symbols = config('game-slots.symbols');
        $reels = config('game-slots.reels');

        foreach($symbols as &$sym){
            $syms[] = asset('storage/games/slots/' . $sym['filename']);
            $sym['filename'] = asset('storage/games/slots/' . $sym['filename']);
            $paytable[] = [
                'scatter'       => $sym['scatter'],
                'wild'          => $sym['wild'],
                'w1'            => $sym['w1']?( ($sym['w1t']=='x'?'x':'').$sym['w1'] ):'',
                'w2'            => $sym['w2']?( ($sym['w2t']=='x'?'x':'').$sym['w2'] ):'',
                'w3'            => $sym['w3']?( ($sym['w3t']=='x'?'x':'').$sym['w3'] ):'',
                'w4'            => $sym['w4']?( ($sym['w4t']=='x'?'x':'').$sym['w4'] ):'',
                'w5'            => $sym['w5']?( ($sym['w5t']=='x'?'x':'').$sym['w5'] ):'',
            ];
        }

        $game = $gameSlotsService->init()->get();
		$game->loadMissing(['account']);
        return view('game-slots::frontend.pages.game', [
            'options'=>[
				'game'                  => $game,
				'preloaderImgUrl'       => asset('images/preloader/' . config('settings.theme') . '/preloader.svg'),
				'config' => [
					'minBet'            => config('game-slots.min_bet'),
					'maxBet'            => config('game-slots.max_bet'),
					'betChangeAmount'   => config('game-slots.bet_change_amount'),
					'defaultBetAmount'  => config('game-slots.default_bet'),
                    'defaultLines'      => config('game-slots.default_lines'),
					'images_path'       => asset('storage/games/slots/'),
                    'lines'             => GameSlotsService::lines,
                    'symbols'           => $symbols,
                    'reels'             => $reels,
                    'syms'              => $syms,
                    'paytable'          => $paytable,
                    'free_games'        => GameFreeSlots::where('account_id', $request->user()->account->id)->where('quantity', '>', 0)->first(),

                    'animation'         => asset('images/games/slots/animation.svg'),

                    'animation_color_border' => 'red',
                    'animation_color_fill' => 'yellow',

                    'animation_frames'  => 14,
                    'animation_time'    => 28,
                    'animation_size'    => 28.6,
                    'sym_size'          => 200,
                    'speed_max'         => 5000
				],
				'routes' => [
					'play'       		=> route('games.slots.play'),
				],
				'sounds' => [
					'click'             => asset('audio/games/slots/click.wav'),
					'lose'              => asset('audio/games/slots/lose.wav'),
					'spin'              => asset('audio/games/slots/spin.wav'),
					'start'             => asset('audio/games/slots/start.wav'),
					'stop'              => asset('audio/games/slots/stop.wav'),
					'win'   	        => asset('audio/games/slots/win.wav'),
				]
			]
        ]);
    }

    public function play(PlayGame $request, GameSlotsService $gameSlotsService)
    {
        return $gameSlotsService
            ->load($request->game_id)
            ->setGameProperty('client_seed', $request->seed)
            ->play($request->only(['lines_count', 'bet']))
            ->get();
    }
}
