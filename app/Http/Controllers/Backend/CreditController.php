<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreDebitCredit;
use App\Models\Account;
use App\Models\Credit;
use App\Services\AccountService;

class CreditController extends Controller
{
    public function create(Account $account)
    {
        return view('backend.pages.accounts.credit', [
            'account' => $account
        ]);
    }

    public function store(StoreDebitCredit $request, Account $account)
    {
        $transactionable = new Credit();
        $transactionable->account()->associate($account);
        $transactionable->amount = $request->amount;
        $transactionable->save();

        $accountService = new AccountService($account);
        $accountService->transaction($transactionable, $transactionable->amount);

        return redirect()->route('backend.accounts.index')->with('success', __('User account is successfully credited.'));
    }
}
