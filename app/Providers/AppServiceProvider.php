<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Observers\OrderItemObserver;
use App\Observers\OrderObserver;
use App\Services\CurrentOutletService;
use App\Services\ShiftService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Order::observe(OrderObserver::class);
        OrderItem::observe(OrderItemObserver::class);

        $staffLayoutComposer = function ($view) {
            if (! auth()->check()) {
                $view->with([
                    'currentShift' => null,
                    'currentOutlet' => null,
                    'accessibleOutlets' => collect(),
                    'pendingCount' => 0,
                ]);

                return;
            }

            $outletService = app(CurrentOutletService::class);
            $outlet = $outletService->current();
            $view->with([
                'currentShift' => app(ShiftService::class)->currentShift(auth()->user(), $outlet->id),
                'currentOutlet' => $outlet,
                'accessibleOutlets' => $outletService->accessibleOutlets(auth()->user()),
                'pendingCount' => app(\App\Services\OrderService::class)->pendingQuery($outlet->id)->count(),
            ]);
        };

        View::composer('layouts.app', $staffLayoutComposer);
        View::composer('layouts.pos-cashier', $staffLayoutComposer);
    }
}
