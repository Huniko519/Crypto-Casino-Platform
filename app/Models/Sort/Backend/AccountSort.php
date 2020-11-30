<?php

namespace App\Models\Sort\Backend;

use App\Models\Sort\Sort;

class AccountSort extends Sort
{
    protected $sortableColumns = [
        'id'                => 'id',
        'account'           => 'code',
        'balance'           => 'balance',
        'status'            => 'status',
        'created'           => 'created_at',
        'updated'           => 'updated_at',
    ];
}