<?php

use App\Models\Outlet;
use App\Services\CurrentOutletService;

if (! function_exists('current_outlet')) {
    function current_outlet(): Outlet
    {
        return app(CurrentOutletService::class)->current();
    }
}

if (! function_exists('current_outlet_id')) {
    function current_outlet_id(): int
    {
        return app(CurrentOutletService::class)->currentId();
    }
}
