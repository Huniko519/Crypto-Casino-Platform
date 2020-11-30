<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Formatters\Formatter;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    use Formatter;

    private $min = 5;
    private $max = 90;
    private $cacheTime;

    public function __construct()
    {
        $this->cacheTime = config('settings.backend.dashboard.cache_time');
    }

    public function index()
    {
        $userStats = Cache::remember('users.stats', $this->cacheTime, function() {
            return User::selectRaw('DATEDIFF(now(),MIN(created_at)) AS days_since_first_sign_up,
                    MAX(CAST(created_at AS DATETIME)) AS last_signed_up_at,
                    COUNT(*) AS count')
                ->first();
        });

        // user sign ups by date [ [date] => ... [value] => ... ]
        $userSignUpsByDay = Cache::remember('users.sign_ups_by_day', $this->cacheTime, function() use($userStats) {
            // create a collection with increasing days
            $timeSeries = Utils::timeSeries(min($this->max, max($this->min, $userStats['days_since_first_sign_up'])));

            return $timeSeries->merge(User::selectRaw('CAST(created_at AS DATE) AS date, COUNT(*) AS value')
                ->where('created_at', '>', Carbon::now()->subDays($timeSeries->count()-1))
                ->groupBy('date')
                ->get()
                ->keyBy('date'))
                ->values();
        });

        $userSignUpsBySource = Cache::remember('users.sing_ups_by_source', $this->cacheTime, function() {
            return User::selectRaw(sprintf('IF(referrer_id IS NULL,"%s","%s") AS category, COUNT(*) AS value', __('Direct sign-ups'), __('Referral sign-ups')))
                ->groupBy('category')
                ->orderBy('value', 'desc')
                ->get();
        });

        $usersByRole = Cache::remember('users.by_role', $this->cacheTime, function() {
            return User::selectRaw('role AS category, COUNT(*) AS value')
                ->groupBy('category')
                ->orderBy('value', 'desc')
                ->get()
                ->transform(function($item) {
                    $item['category'] = __('app.user_role_' . $item['category']);
                    return $item;
                });
        });

        return view('backend.pages.dashboard.users', [
            'users_count'               => $this->integer($userStats['count']),
            'signed_up_last_week'       => $this->integer($userSignUpsByDay->take(-7)->sum('value')),
            'last_signed_up_at'         => Carbon::createFromTimeString($userStats['last_signed_up_at'])->diffForHumans(),
            'sign_ups_by_day'           => $userSignUpsByDay,
            'sign_ups_by_source'        => $userSignUpsBySource,
            'users_by_role'             => $usersByRole,
        ]);
    }

    public function games()
    {
        $stats = Cache::remember('games.stats', $this->cacheTime, function() {
            return Game::where('status', Game::STATUS_COMPLETED)
                ->selectRaw('DATEDIFF(now(),MIN(created_at)) AS days_since_first_game,
                    AVG(bet) AS avg_bet,
                    MAX(bet) AS max_bet,
                    SUM(bet) AS total_bet,
                    AVG(win-bet) AS avg_net_win,
                    GREATEST(0,MAX(win-bet)) AS max_net_win,
                    SUM(win-bet) AS total_net_win,
                    COUNT(*) AS count')
                ->first();
        });

        $timeSeries = Utils::timeSeries(min($this->max, max($this->min, $stats['days_since_first_game'])));

        $playedByDay = Cache::remember('games.played_by_day', $this->cacheTime, function() use($timeSeries) {
            return $timeSeries->merge(Game::selectRaw('CAST(updated_at AS DATE) AS date, COUNT(*) AS value')
                ->where('created_at', '>', Carbon::now()->subDays($timeSeries->count()-1))
                ->where('status', Game::STATUS_COMPLETED)
                ->groupBy('date')
                ->get()
                ->keyBy('date'))
                ->values();
        });

        $netWinByDay = Cache::remember('games.net_win_by_day', $this->cacheTime, function() use($timeSeries) {
            return $timeSeries->merge(Game::selectRaw('CAST(updated_at AS DATE) AS date, SUM(win-bet) AS value')
                ->where('created_at', '>', Carbon::now()->subDays($timeSeries->count()-1))
                ->where('status', Game::STATUS_COMPLETED)
                ->groupBy('date')
                ->get()
                ->keyBy('date'))
                ->values();
        });

        $playedByType = Cache::remember('games.played_by_type', $this->cacheTime, function() {
            return Game::selectRaw('REPLACE(gameable_type,"GameMultiSlots","GameSlots") as category, COUNT(*) AS value')
                ->where('status', Game::STATUS_COMPLETED)
                ->groupBy('category')
                ->orderBy('value', 'desc')
                ->get()
                ->transform(function($item) {
                    return [
                        'category' => __('app.game_' . class_basename($item['category'])),
                        'value' => $item['value']
                    ];
                });
        });

        $playedByTypeExtended = Cache::remember('games.played_by_type_ext', $this->cacheTime, function() {
            return Game::selectRaw('REPLACE(gameable_type,"GameMultiSlots","GameSlots") as game,
                SUM(bet) AS bet,
                SUM(win) AS win,
                100*SUM(win)/SUM(bet) AS return_to_player,
                100-100*SUM(win)/SUM(bet) AS house_edge')
                ->where('status', Game::STATUS_COMPLETED)
                ->groupBy('game')
                ->orderBy('game', 'asc')
                ->get()
                ->transform(function($item) {
                    $item['game'] = __('app.game_' . class_basename($item['game']));
                    return $item;
                });
        });

        $playedByResult = Cache::remember('games.played_by_result', $this->cacheTime, function() {
            return Game::selectRaw(sprintf('IF(win>bet,"%s","%s") as category, COUNT(*) AS value', __('Win'), __('Loss')))
                ->where('status', Game::STATUS_COMPLETED)
                ->groupBy('category')
                ->orderBy('value', 'desc')
                ->get();
        });

        $topWins = Cache::remember('games.top_wins', $this->cacheTime, function() {
            return Game::selectRaw('games.*, win-bet AS net_win')
                ->where('status', Game::STATUS_COMPLETED)
                ->with('account.user')
                ->orderBy('net_win', 'desc')
                ->limit(5)
                ->get();
        });

        $topLosses = Cache::remember('games.top_losses', $this->cacheTime, function() {
            return Game::selectRaw('games.*, win-bet AS net_loss')
                ->where('status', Game::STATUS_COMPLETED)
                ->with('account.user')
                ->orderBy('net_loss', 'asc')
                ->limit(5)
                ->get();
        });

        return view('backend.pages.dashboard.games', [
            'games_count'           => $this->integer($stats['count']),
            'played_last_month'     => $this->integer($playedByDay->take(-30)->sum('value')),
            'played_last_week'      => $this->integer($playedByDay->take(-7)->sum('value')),

            'avg_bet'               => $this->integer($stats['avg_bet']),
            'max_bet'               => $this->integer($stats['max_bet']),
            'total_bet'             => $this->integer($stats['total_bet']),

            'avg_net_win'           => $this->integer($stats['avg_net_win']),
            'max_net_win'           => $this->integer($stats['max_net_win']),
            'total_net_win'         => $this->integer($stats['total_net_win']),

            'played_by_day'         => $playedByDay,
            'net_win_by_day'        => $netWinByDay,
            'played_by_type'        => $playedByType,
            'played_by_type_ext'    => $playedByTypeExtended,
            'played_by_result'      => $playedByResult,
            'top_wins'              => $topWins,
            'top_losses'            => $topLosses,
        ]);
    }

    public function accounts()
    {
        $stats = Cache::remember('accounts.stats', $this->cacheTime, function() {
            return Account::selectRaw('AVG(balance) AS avg_balance,
                    MAX(balance) AS max_balance,
                    SUM(balance) AS total_balance')
                ->first();
        });

        $transactionsByType = Cache::remember('transactions.by_type', $this->cacheTime, function() {
            // TODO: refactor to use $transactionable->title attribute
            return AccountTransaction::selectRaw('transactionable_type as category, COUNT(*) AS value')
                ->groupBy('category')
                ->orderBy('value', 'desc')
                ->get()
                ->transform(function($item) {
                    return [
                        'category' => __('app.transaction_type_' . class_basename($item['category'])),
                        'value' => $item['value']
                    ];
                });
        });

        $topTransactions = Cache::remember('transactions.top', $this->cacheTime, function() {
            return AccountTransaction::orderBy('amount', 'desc')->with('account.user')->limit(10)->get();
        });

        return view('backend.pages.dashboard.accounts', [
            'avg_balance'               => $this->integer($stats['avg_balance']),
            'max_balance'               => $this->integer($stats['max_balance']),
            'total_balance'             => $this->integer($stats['total_balance']),
            'transactions_by_type'      => $transactionsByType,
            'top_transactions'          => $topTransactions,
        ]);
    }
}
