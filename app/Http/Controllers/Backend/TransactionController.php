<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\Sort\Backend\TransactionSort;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $sort = new TransactionSort($request);
        $type = $request->query('type');

        $types = AccountTransaction::select('transactionable_type')
            ->distinct()
            ->get()
            ->keyBy('transactionable_type')->map(function ($item, $key) {
                return __($item->title);
            });

        $transactions = AccountTransaction::with('account', 'account.user', 'transactionable')
            ->when($type, function($query, $type) {
                $query->where('transactionable_type', $type);
            })
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('backend.pages.transactions.index', [
            'transactions'  => $transactions,
            'types'         => $types,
            'sort'          => $sort->getSort(),
            'order'         => $sort->getOrder(),
        ]);
    }
}
