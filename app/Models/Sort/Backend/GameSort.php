<?php


namespace App\Models\Sort\Backend;


use App\Models\Sort\Sort;

class GameSort extends Sort
{
    protected $sortableColumns = [
        'id'     => 'id',
        'game'   => 'gameable_type',
        'bet'    => 'bet',
        'win'    => 'win',
        'played' => 'updated_at',
    ];

    protected $defaultSort = 'played';
}