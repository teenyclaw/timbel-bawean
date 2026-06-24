<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function periodStats(?int $outletId, string $from, string $to): array
    {
        $base = $this->paidOrdersQuery($outletId, $from, $to);

        $orders = (clone $base)->count();
        $revenue = (int) (clone $base)->sum('total');

        $payments = OrderPayment::query()
            ->where('status', OrderPayment::STATUS_PAID)
            ->whereBetween('paid_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->whereHas('order', fn ($q) => $q->when($outletId, fn ($q2) => $q2->where('outlet_id', $outletId)))
            ->get();

        return [
            'orders' => $orders,
            'revenue' => $revenue,
            'payments' => $payments->count(),
            'cash' => (int) $payments->where('payment_method', 'cash')->sum('amount'),
            'transfer' => (int) $payments->where('payment_method', 'transfer')->sum('amount'),
            'qris' => (int) $payments->where('payment_method', 'qris')->sum('amount'),
        ];
    }

    public function topItemsForPeriod(?int $outletId, string $from, string $to, int $limit = 10): array
    {
        return OrderItem::query()
            ->select('item_name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(qty * price) as total_revenue'))
            ->whereHas('order', function ($q) use ($outletId, $from, $to) {
                $q->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->whereIn('status', [OrderStatus::Paid->value, OrderStatus::Completed->value])
                    ->when($outletId, fn ($q2) => $q2->where('outlet_id', $outletId));
            })
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function ordersQuery(?int $outletId, string $from, string $to): Builder
    {
        return Order::with(['items', 'diningTable'])
            ->when($outletId, fn ($q) => $q->where('outlet_id', $outletId))
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->orderByDesc('created_at');
    }

    public function paidOrdersQuery(?int $outletId, string $from, string $to): Builder
    {
        return Order::query()
            ->when($outletId, fn ($q) => $q->where('outlet_id', $outletId))
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->whereIn('status', [OrderStatus::Paid->value, OrderStatus::Completed->value]);
    }
}
