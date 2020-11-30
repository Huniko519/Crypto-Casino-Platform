<th class="{{ $id == $sort ? ($order == 'asc' ? 'sort-asc' : 'sort-desc') : '' }} {{ isset($class) ? $class : '' }}">
    @if($id == $sort)
        @if($order == 'asc')
            <i class="fas fa-arrow-up"></i>
        @else
            <i class="fas fa-arrow-down"></i>
        @endif
    @endif
    <a href="{{ route(Route::currentRouteName(), array_merge(request()->query(), ['sort' => $id, 'order' => $id != $sort ? 'asc' : ($order == 'asc'  ? 'desc' : 'asc')])) }}">{{ $slot }}</a>
</th>