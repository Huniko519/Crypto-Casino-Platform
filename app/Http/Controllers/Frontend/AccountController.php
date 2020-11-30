<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $transactions = $user->account->transactions()
            ->orderBy('id', 'desc')
            ->paginate($this->rowsPerPage);

        return view('frontend.pages.account.show', [
            'account'               => $user->account,
            'transactions'          => $transactions
        ]);
    }
}
