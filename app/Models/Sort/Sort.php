<?php

namespace App\Models\Sort;

use Illuminate\Http\Request;

class Sort
{
    private $request;

    /**
     * Associative array of sortable columns, e.g.:
     * ['id' => 'trades.id', 'name' => 'assets.name']
     *
     * @var array
     */
    protected $sortableColumns = [];

    /**
     * Default sort column
     * @var string
     */
    protected $defaultSort = 'id';

    /**
     * Default sort order (asc / desc)
     * @var string
     */
    protected $defaultOrder = 'desc';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get sort column id
     *
     * @param Request $request
     * @return string
     */
    public function getSort() {
        $sort = (string) $this->request->input('sort');
        return array_key_exists($sort, $this->sortableColumns)
                ? $sort
                : $this->defaultSort;
    }

    /**
     * Get sort column name to use in orderBy() query
     *
     * @return string
     */
    public function getSortColumn() {
        $sort = (string) $this->request->input('sort');
        return array_key_exists($sort, $this->sortableColumns)
                ? $this->sortableColumns[$sort]
                : $this->sortableColumns[$this->defaultSort];
    }

    /**
     * Get sort direction
     *
     * @param Request $request
     * @return string
     */
    public function getOrder() {
        $order = (string) $this->request->input('order');
        return in_array($order, ['asc','desc']) ? $order : $this->defaultOrder;
    }
}