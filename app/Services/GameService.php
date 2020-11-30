<?php

namespace App\Services;

use App\Events\GamePlayed;
use App\Models\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class GameService
{
    protected $user;
    protected $gameableModel;
    protected $game;

    // it's important NOT to typehint $user object to App\Models\User, otherwise the passed object will never be NULL
    public function __construct($user = NULL)
    {
        if (!$this->gameableModel)
            throw new \Exception('Gameable model should be explicitly set in the child class before calling GameService constructor.');

        $this->user = $user ?: auth()->user();
    }

    /**
     * Init service and return a Game model instance (create one if it doesn't exist)
     *
     * @return GameService
     * @throws \Exception
     */
    public function init(): GameService
    {
        // try to find already existing model first
        $this->game = Game::where([
            'gameable_type' => $this->gameableModel,
            'status'        => Game::STATUS_CREATED,
            'account_id'    => $this->user->account->id
        ])->orderBy('id', 'desc')->first();

        // create a new game if it doesn't exist
        if (!$this->game)
            $this->game = $this->create();

        return $this;
    }

    /**
     * Create new Game model instance without overwriting the current one (that's why new child GameService class instance is required)
     *
     * @return Game
     */
    protected function createNextGame(): Game
    {
        $childGameService = get_called_class();
        // it's important to pass $user object for playing games by bots,
        // because in this case user will not be taken from the session.
        return (new $childGameService($this->user))->create();
    }

    /**
     * Create a new Game model instance along with related Gameable model instance
     *
     * @return Game
     * @throws \Exception
     */
    protected function create(): Game
    {
        // create gameable first (Slots, Video Poker, Blackjack etc)
        $gameable = $this->createGameable();

        // create game model
        $this->game = new Game();
        $this->game->account()->associate($this->user->account);
        $this->game->bet = 0;
        $this->game->win = 0;
        $this->game->secret = $this->makeSecret(); // game specific server secret
        $this->game->server_seed = $this->makeServerSeed();
        $this->game->status = Game::STATUS_CREATED;

        $gameable->game()->save($this->game);

        return $this->game;
    }

    /**
     * Load game model from DB
     *
     * @param $gameId
     * @return GameService
     */
    public function load($gameId): GameService
    {
        $this->game = Game::where('id', $gameId)->with('gameable')->first();

        return $this;
    }

    /**
     * Set game property (attribute) to a certain value
     *
     * @param string $property
     * @param $value
     * @return GameService
     */
    public function setGameProperty(string $property, $value): GameService
    {
        $this->game->$property = $value;

        return $this;
    }

    /**
     * Generate server seed
     *
     * @return string
     */
    protected function makeServerSeed(): string
    {
        return Str::random(16);
    }


    /**
     * Save game bet and update user account balance (for multi-round games like Video Poker, Blackjack)
     *
     * @param Model $gameable
     * @param float $bet
     */
    protected function saveBet(float $bet): GameService
    {
        $this->game->bet = $bet;
        $this->game->status = Game::STATUS_STARTED;

        $this->game->gameable->save(); // save gameable model (e.g. Video Poker)
        $this->game->save();

        // create account transaction
        $accountService = new AccountService($this->game->account);
        $accountService->transaction($this->game, -$this->game->bet);

        return $this;
    }

    /**
     * Adjust already saved bet (can be used when increasing the initial bet, for example double in Blackjack)
     *
     * @param float $betAdjustment
     * @return GameService
     */
    protected function adjustBet(float $betAdjustment): GameService
    {
        $this->game->bet = $this->game->bet + $betAdjustment;

        $this->game->gameable->save(); // save gameable model (e.g. Video Poker)
        $this->game->save();

        // create account transaction
        $accountService = new AccountService($this->game->account);
        $accountService->transaction($this->game, -$betAdjustment);

        return $this;
    }

    /**
     * Save game result and update user account balance (for multi-round games like Video Poker, Blackjack)
     *
     * @param float $win
     * @return GameService
     */
    protected function saveResult(float $win): GameService
    {
        $this->game->win = $win;
        $this->game->status = Game::STATUS_COMPLETED;

        $this->game->gameable->save(); // save child game (e.g. slots)
        $this->game->save();

        // create account transaction
        $accountService = new AccountService($this->game->account);
        $accountService->transaction($this->game, $this->game->win);

        $this->game->next_game = $this->createNextGame();

        // throw new GamePlayed event
        event(new GamePlayed($this->game));

        return $this;
    }

    /**
     * Save game results and update user account balance (for single round games like Slots, Roulette)
     *
     * @param float $bet
     * @param float $win
     * @return GameService
     */
    protected function complete(float $bet, float $win): GameService
    {
        $this->game->bet = $bet;
        $this->game->win = $win;
        $this->game->status = Game::STATUS_COMPLETED;

        $this->game->gameable->save(); // save child game (e.g. slots)
        $this->game->save();

        // create account transaction
        $accountService = new AccountService($this->game->account);
        $accountService->transaction($this->game, $this->game->win - $this->game->bet);

        $this->game->next_game = $this->createNextGame();

        // throw new GamePlayed event
        event(new GamePlayed($this->game));

        return $this;
    }

    protected function completeFreeGame(float $bet, float $win): GameService
    {
        $this->game->bet = $bet;
        $this->game->win = $win;
        $this->game->status = Game::STATUS_COMPLETED;

        $this->game->gameable->save(); // save child game (e.g. slots)
        $this->game->save();

        // create account transaction
        $accountService = new AccountService($this->game->account);
        $accountService->transaction($this->game, $this->game->win);

        $this->game->next_game = $this->createNextGame();

        // throw new GamePlayed event
        event(new GamePlayed($this->game));

        return $this;
    }

    /**
     * Get Game model instance
     *
     * @return Game
     */
    public function get(): Game
    {
        return $this->game;
    }

    /**
     * Create a Gameable model instance (Slots, Poker etc)
     *
     * @return Model
     */
    abstract protected function createGameable(): Model;

    /**
     * Make a game secret (reels positions, shuffled cards deck) - before applying client seed
     *
     * @return string
     */
    abstract protected function makeSecret(): string;

    /**
     * Apply client seed and return modified secret (adjust reel positions, cut the cards deck)
     *
     * @return string
     */
    abstract protected function adjustSecret(): string;
}
