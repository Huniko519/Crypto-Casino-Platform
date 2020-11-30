<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\PackageManager;
use App\Helpers\Utils;
use App\Models\Game;
use App\Models\Sort\Backend\GameSort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $sort = new GameSort($request);
        $uid = $request->query('uid');

        $games = Game::where('status', Game::STATUS_COMPLETED)
            // when user ID is provided
            ->when($uid, function($query, $uid) {
                // query related user model
                $query->whereHas('account.user', function($query) use($uid) {
                    $query->where('id', $uid);
                });
            })
            ->with('account.user', 'gameable')
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('backend.pages.games.index', [
            'games'         => $games,
            'sort'          => $sort->getSort(),
            'order'         => $sort->getOrder(),
        ]);
    }

    public function show(Game $game, PackageManager $packageManager)
    {
        $packageId = Utils::classId($game->gameable);
        $package = $packageManager->get($packageId);

        if (!$package || $packageManager->disabled($packageId))
            return back()->withErrors(__('Please enable ":id" add-on to view the game details.', ['id' => $packageId]));

        return view('backend.pages.games.show', [
            'game'              => $game,
            'game_package_id'   => Utils::classId($game->gameable) // GameSlots => game-slots
        ]);
    }
}
