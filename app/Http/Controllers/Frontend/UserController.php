<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\UpdatePassword;
use App\Http\Requests\Frontend\UpdateUser;
use App\Models\Formatters\Formatter;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use Formatter;

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $account = $user->account;

        // collect user games
        $games = $account ?
            Cache::remember('users.' . $user->id . '.games', 5, function() use($account) {
                return $account->games()->with('gameable')->get();
            }) :
            collect();

        $totalPlayed    = $games->count();
        $totalWon       = $games->where('net_win', '>', 0)->count();

        $gamesByResult = [
            ['category' => __('Win'),  'value' => $totalWon],
            ['category' => __('Loss'), 'value' => $totalPlayed - $totalWon]
        ];

        $gamesByType = $games->groupBy(function ($item, $key) {
            return __('app.game_' . str_replace('GameMultiSlots', 'GameSlots', class_basename($item['gameable_type'])));
        })->map(function($item, $key) {
            $maxWin = max(0, $item->max('win'));
            return [
                'category'      => $key,
                'value'         => $item->count(), // number of played games
                'max_win'   => $maxWin,
                '_max_win'  => $this->decimal($maxWin)
            ];
        })->sortByDesc('value')->values();

        return view('frontend.pages.users.show', [
            'user'              => $user,
            'games_by_result'   => $gamesByResult,
            'games_by_type'     => $gamesByType,
            'recent_games'      => $totalPlayed > 0 ? $games->sortByDesc('updated_at')->take(15) : collect(),
            'total_played'      => $totalPlayed,
            'last_played'       => $totalPlayed > 0 ? $games->sortByDesc('updated_at')->first()->updated_at : NULL,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        return view('frontend.pages.users.edit', ['user' => $request->user()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request)
    {
        $user = $request->user();
        // validator passed, update fields
        $user->name = $request->name;
        // update email only if the user doesn't have linked social profiles
        if ($user->profiles->isEmpty()) {
            $user->email = $request->email;
        }
        $user->save();

        return redirect()
            ->route('frontend.users.show', $user)
            ->with('success', __('Your profile is successfully saved'));
    }

    public function editPassword(Request $request)
    {
        return view('frontend.pages.users.password', ['user' => $request->user()]);
    }

    public function updatePassword(UpdatePassword $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('frontend.users.show', $user)
            ->with('success', __('Your password is successfully saved'));
    }
}
