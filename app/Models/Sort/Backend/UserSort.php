<?php

namespace App\Models\Sort\Backend;

use App\Models\Sort\Sort;

class UserSort extends Sort
{
    protected $sortableColumns = [
        'id'                => 'id',
        'name'              => 'name',
        'email'             => 'email',
        'games'             => 'games_count',
        'status'            => 'status',
        'last_login_at'     => 'last_login_at',
    ];
}