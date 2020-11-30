<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Sort\Backend\AccountSort;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = new AccountSort($request);
        $search = $request->query('search');

        $accounts = Account::with('user')
            // when search is provided
            ->when($search, function($query, $search) {
                // query related user model
                $query->whereHas('user', function($query) use($search) {
                    $query
                        ->whereRaw('LOWER(name) LIKE ?', ['%'. strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(email) LIKE ?', ['%'. strtolower($search) . '%']);
                });
            })
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('backend.pages.accounts.index', [
            'accounts'  => $accounts,
            'search'    => $search,
            'sort'      => $sort->getSort(),
            'order'     => $sort->getOrder(),
        ]);
    }

    public function transactions(Account $account)
    {
        $transactions = $account
            ->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate($this->rowsPerPage);

        return view('backend.pages.accounts.transactions', [
            'account'       => $account,
            'transactions'  => $transactions,
        ]);
    }
}
