<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\UpdateUser;
use App\Models\Sort\Backend\UserSort;
use App\Models\User;
use App\Notifications\UserMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, User $user)
    {
        $sort = new UserSort($request);
        $search = $request->query('search');

        $users = User::with('referrer')
            ->withCount(['games AS total_bet' => function ($query) {
                $query->select(DB::raw('IFNULL(SUM(bet),0)'));
            }])
            ->withCount('games')
            // when search is provided
            ->when($search, function($query, $search) {
                $query
                    ->whereRaw('LOWER(name) LIKE ?', ['%'. strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%'. strtolower($search) . '%']);
            })
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('backend.pages.users.index', [
            'users'     => $users,
            'search'    => $search,
            'sort'      => $sort->getSort(),
            'order'     => $sort->getOrder(),
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('backend.pages.users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        // validator passed, update fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->referee_sign_up_credits = $request->individual_referral_bonuses == 'on' ? $request->referee_sign_up_credits : NULL;
        $user->referrer_sign_up_credits = $request->individual_referral_bonuses == 'on' ? $request->referrer_sign_up_credits : NULL;
        $user->referrer_deposit_pct = $request->individual_referral_bonuses == 'on' ? $request->referrer_deposit_pct : NULL;
        $user->referrer_game_loss_pct = $request->individual_referral_bonuses == 'on' ? $request->referrer_game_loss_pct : NULL;
        $user->referrer_game_win_pct = $request->individual_referral_bonuses == 'on' ? $request->referrer_game_win_pct : NULL;

        // update password if it was filled in
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()
            ->route('backend.users.index', request()->query())
            ->with('success', __('User :name saved.', ['name' => $user->name]));
    }

    /**
     * Display a page to confirm deleting a user
     *
     * @param  \App\Models\User  $user
     */
    public function delete(Request $request, User $user) {
        // check that the user being deleted is not current
        if ($request->user()->id == $user->id) {
            return redirect()
                ->back()
                ->withErrors(['user' => __('You can not delete currently authenticated user.')]);
        }

        $request->session()->flash('warning', __('Please note that after deleting the user all related data (accounts, transactions etc) will also be removed. This action is irreversible.'));
        return view('backend.pages.users.delete', ['user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        // check that the user being deleted is not current
        if ($request->user()->id == $user->id) {
            return redirect()
                ->back()
                ->withErrors(['user' => __('You can not delete currently authenticated user.')]);
        }

        $userName = $user->name;

        // delete user
        $user->delete();

        return redirect()
            ->route('backend.users.index')
            ->with('success', __('User :name has been deleted.', ['name' => $userName]));
    }

    /**
     * Show send mail page
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mailShow(User $user)
    {
        return view('backend.pages.users.mail', ['user' => $user]);
    }

    /**
     * Send mail
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function mailSend(Request $request, User $user)
    {
        try {
            $user->notify(new UserMessage($request->subject, $request->greeting, $request->message));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(__('There was an error while sending the email, please check the app logs for more details.'));
        }

        return redirect()
            ->route('backend.users.index', $request->query())
            ->with('success', __('Message is successfully sent.'));
    }
}
