<form method="GET" action="{{ route($route) }}">
    <div class="input-group">
        <input name="search" type="text" class="form-control" placeholder="{{ __('Search...') }}" value="{{ $search }}">
        <div class="input-group-append">
            @if($search)
                <a class="btn btn-info" href="{{ route($route) }}">
                    <i class="fa fa-times"></i>
                </a>
            @endif
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
</form>