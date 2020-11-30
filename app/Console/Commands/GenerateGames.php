<?php

namespace App\Console\Commands;

use App\Events\CommandExecuted;
use App\Helpers\Games\NumberGenerator;
use App\Helpers\PackageManager;
use App\Models\Game;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Packages\GameDice3D\Services\GameDice3DService;

class GenerateGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:games';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate games (each selected bot will play one game)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $count = random_int(config('settings.bots.game.count_min'), config('settings.bots.game.count_max'));

        // retrieve bots
        $users = User::where('role', User::ROLE_BOT)
            ->where('status', User::STATUS_ACTIVE)
            ->inRandomOrder()
            ->limit($count)
            ->get();

        // if bots exist
        if (!$users->isEmpty()) {
            // get all game service classes
            $gameServiceClasses = [];
            $packageManager = new PackageManager();
            foreach ($packageManager->getEnabled() as $package) {
                if (strpos($package->id, 'game-') !== FALSE) {
                    $gameServiceClass = $package->namespace
                        . 'Services\\'
                        // Str::camel('dice-3d') = dice3d, but "d" needs to be capitalized, therefore using extra preg_replace()
                        . ucfirst(Str::camel(preg_replace('#-([0-9]+)#', '$1-', $package->id)))
                        . 'Service';
                    if (class_exists($gameServiceClass))
                        $gameServiceClasses[] = $gameServiceClass;
                }
            }

            $n = count($gameServiceClasses);

            if ($n > 0) {
                $minBet = intval(config('settings.bots.game.min_bet'));
                $maxBet = intval(config('settings.bots.game.max_bet'));

                // loop through bots users
                foreach ($users as $user) {
                    $i = random_int(0, $n - 1);
                    $seed = random_int(10000,99999);
                    $gameServiceClass = $gameServiceClasses[$i];
                    $gameService = new $gameServiceClass($user);
                    $gameService->init()->setGameProperty('client_seed', $seed);

                    // slots
                    if ($gameServiceClass == 'Packages\GameSlots\Services\GameSlotsService') {
                        $gameService
                            ->play([
                                'lines_count' => random_int(1, 20),
                                'bet' => random_int($minBet ?: config('game-slots.min_bet'), $maxBet ?: config('game-slots.max_bet'))
                            ]);

                    // multi-slots
                    } else if ($gameServiceClass == 'Packages\GameMultiSlots\Services\GameMultiSlotsService') {
                        $gameService
                            ->play([
                                'lines_count'   => random_int(1, 20),
                                'bet'           => random_int($minBet ?: config('game-multi-slots.min_bet')[0], $maxBet ?: config('game-multi-slots.max_bet')[0])
                            ]);

                    // video poker
                    } elseif ($gameServiceClass == 'Packages\GameVideoPoker\Services\GameVideoPokerService') {
                        $hold = [];
                        // randomly hold or not
                        if (random_int(0,1)==1) {
                            foreach ([0, 1, 2, 3, 4] as $item) {
                                // hold only random cards
                                if (random_int(0, 1) == 1)
                                    $hold[] = $item;
                            }
                        }
                        $gameService
                            ->draw([
                                'bet_coins'   => random_int(1, 5),
                                'bet_amount'  => random_int($minBet ?: config('game-video-poker.min_bet'), $maxBet ?: config('game-video-poker.max_bet'))
                            ])
                            ->play(['hold' => $hold]);

                    // blackjack
                    } elseif ($gameServiceClass == 'Packages\GameBlackjack\Services\GameBlackjackService') {
                        $game = $gameService
                            ->deal(['bet' => random_int($minBet ?: config('game-blackjack.min_bet'), $maxBet ?: config('game-blackjack.max_bet'))])
                            ->get();

                        // hit until player gets 17 or more
                        while($gameService->get()->gameable->player_score < 17) {
                            $gameService->hit();
                        }

                        // stand if not busted
                        if ($game->status != Game::STATUS_COMPLETED)
                            $gameService->stand();

                    // casino holdem
                    } elseif ($gameServiceClass == 'Packages\GameCasinoHoldem\Services\GameCasinoHoldemService') {
                        $gameService
                            ->deal([
                                'bet' => random_int($minBet ?: config('game-casino-holdem.min_bet'), $maxBet ?: config('game-casino-holdem.max_bet')),
                                'bonus_bet' => random_int(intval(config('game-casino-holdem.min_bonus_bet')), intval(config('game-casino-holdem.max_bonus_bet')))
                            ])
                            ->get();

                        if (random_int(0, 1) == 0) {
                            $gameService->fold();
                        } else {
                            $gameService->call();
                        }

                    // roulette
                    } elseif ($gameServiceClass == 'Packages\GameRoulette\Services\GameRouletteService' || $gameServiceClass == 'Packages\GameAmericanRoulette\Services\GameAmericanRouletteService') {
                        $package = $gameServiceClass == 'Packages\GameRoulette\Services\GameRouletteService' ? 'game-roulette' : 'game-american-roulette';
                        $bets = [];
                        $betsCount = random_int(1, 10);
                        $betTypes = array_keys($gameServiceClass::BET_TYPES);
                        $betTypesCount = count($betTypes);

                        while (count($bets) < $betsCount) {
                            $bet = $betTypes[random_int(0, $betTypesCount-1)];
                            if (!array_key_exists($bet, $bets))
                                $bets[$bet] = random_int($minBet ?: config($package . '.min_bet'), $maxBet ?: config($package . '.max_bet'));
                        }
                        $gameService->play(['bets' => $bets]);
                    // dice
                    } elseif ($gameServiceClass == 'Packages\GameDice\Services\GameDiceService') {
                        $gameService
                            ->play([
                                'direction'     => array_random([-1, 1]),
                                'bet'           => random_int($minBet ?: config('game-dice.min_bet'), $maxBet ?: config('game-dice.max_bet')),
                                'win_chance'    => random_int(config('game-dice.min_win_chance'), config('game-dice.max_win_chance')),
                            ]);
                    // dice 3D
                    } elseif ($gameServiceClass == 'Packages\GameDice3D\Services\GameDice3DService') {
                        $gameService
                            ->play([
                                'direction'     => array_random([-1, 1]),
                                'bet'           => random_int($minBet ?: config('game-dice-3d.min_bet'), $maxBet ?: config('game-dice-3d.max_bet')),
                                'ref_number'    => random_int(GameDice3DService::calcMinRefNumber(), GameDice3DService::calcMaxRefNumber())
                            ]);
                    // heads or tails
                    } elseif ($gameServiceClass == 'Packages\GameHeadsOrTails\Services\GameHeadsOrTailsService') {
                        $gameService
                            ->play([
                                'bet'      => random_int($minBet ?: config('game-heads-or-tails.min_bet'), $maxBet ?: config('game-heads-or-tails.max_bet')),
                                'toss_bet' => array_random([0, 1]),
                            ]);
                    // american bingo
                    } elseif ($gameServiceClass == 'Packages\GameAmericanBingo\Services\GameAmericanBingoService') {
                        $gameService
                            ->play([
                                'bet' => random_int($minBet ?: config('game-american-bingo.min_bet'), $maxBet ?: config('game-american-bingo.max_bet'))
                            ]);
                    // keno
                    } elseif ($gameServiceClass == 'Packages\GameKeno\Services\GameKenoService') {
                        $numbers = [];
                        $ng = new NumberGenerator(1, 80);

                        while(count($numbers) < 10) {
                            $number = $ng->generate()->getNumber();
                            if (!in_array($number, $numbers))
                                $numbers[] = $number;
                        }

                        $gameService
                            ->play([
                                'bet' => random_int($minBet ?: config('game-keno.min_bet'), $maxBet ?: config('game-keno.max_bet')),
                                'bet_numbers' => $numbers
                            ]);
                    // lucky wheel
                    } elseif ($gameServiceClass == 'Packages\GameLuckyWheel\Services\GameLuckyWheelService') {
                        $gameIndex = random_int(0, count(config('game-lucky-wheel.variations'))-1);
                        $gameService
                            ->play([
                                'bet' => random_int($minBet ?: config('game-lucky-wheel.variations')[$gameIndex]->min_bet, $maxBet ?: config('game-lucky-wheel.variations')[$gameIndex]->max_bet)
                            ]);
                    // baccarat
                    } elseif ($gameServiceClass == 'Packages\GameBaccarat\Services\GameBaccaratService') {
                        $gameService
                            ->play([
                                'bet_type'  => random_int(0, 2),
                                'bet'       => random_int($minBet ?: config('game-baccarat.min_bet'), $maxBet ?: config('game-baccarat.max_bet')),
                            ]);
                    }
                }
            }
        }

        event(new CommandExecuted(__CLASS__));
    }
}
