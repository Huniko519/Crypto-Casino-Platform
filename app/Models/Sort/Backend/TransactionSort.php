<?php

namespace App\Models\Sort\Backend;

use App\Models\Sort\Sort;

class TransactionSort extends Sort
{
    protected $sortableColumns = [
        'id'                => 'id',
        'type'              => 'transactionable_type',
        'amount'            => 'amount',
        'created'           => 'created_at',
        'updated'           => 'updated_at',
    ];
}
