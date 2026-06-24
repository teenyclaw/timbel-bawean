<?php

namespace App\Http\Controllers\Pos;

use App\Enums\OrderStatus;
use App\Http\Controllers\Concerns\AssertsCurrentOutlet;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    use AssertsCurrentOutlet;

    public function __construct(private OrderService $orderService) {}

    public function index()
    {
        $outlet = current_outlet();
        $order = $this->orderService->getOrCreateCounterOrder($outlet, auth()->user());

        return view('pos.cashier.index', array_merge(
            $this->menuData($outlet),
            [
                'order' => $order,
                'pendingCount' => $this->orderService->pendingQuery($outlet->id)->count(),
            ]
        ));
    }

    public function newOrder()
    {
        $this->orderService->clearCounterOrderSession(current_outlet());

        return redirect()->route('pos.cashier')->with('success', 'Order baru siap.');
    }

    public function addItem(Request $request, Order $order)
    {
        $this->assertCounterOrder($order);

        $data = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'qty' => 'required|integer|min:1|max:99',
            'note' => 'nullable|string|max:255',
            'option_ids' => 'nullable|array',
            'option_ids.*' => 'integer|exists:modifier_options,id',
        ]);

        $item = MenuItem::where('outlet_id', $order->outlet_id)
            ->where('is_available', true)
            ->with('modifierGroups')
            ->findOrFail($data['menu_item_id']);

        try {
            $this->orderService->appendMenuItem(
                $order,
                $item,
                $data['qty'],
                $data['note'] ?? null,
                $data['option_ids'] ?? []
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $item->name . ' ditambahkan.');
    }

    public function updateItem(Request $request, Order $order, OrderItem $item)
    {
        $this->assertCounterOrder($order);
        abort_unless($item->order_id === $order->id, 404);

        $data = $request->validate([
            'qty' => 'required|integer|min:1|max:99',
        ]);

        try {
            $this->orderService->updateOrderItemQty($item, $data['qty']);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back();
    }

    public function removeItem(Order $order, OrderItem $item)
    {
        $this->assertCounterOrder($order);
        abort_unless($item->order_id === $order->id, 404);

        try {
            $this->orderService->removeOrderItem($item);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Item dihapus.');
    }

    public function submit(Order $order)
    {
        $this->assertCounterOrder($order);

        if ($order->items->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        $this->orderService->submitOpenBill($order);

        return back()->with('success', 'Pesanan dikirim ke dapur.');
    }

    public function pay(Order $order)
    {
        $this->assertCounterOrder($order);

        if ($order->items->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        if ($order->status === OrderStatus::Open->value) {
            $this->orderService->submitOpenBill($order);
        }

        $this->orderService->clearCounterOrderSession($order->outlet);

        return redirect()->route('pos.payment', $order);
    }

    private function assertCounterOrder(Order $order): void
    {
        $this->assertOrderInCurrentOutlet($order);
        abort_unless($order->dining_table_id === null && $this->orderService->isEditableBill($order), 404);
    }

    /** @return array{categories: \Illuminate\Support\Collection, uncategorized: \Illuminate\Support\Collection} */
    private function menuData($outlet): array
    {
        $categories = Category::where('outlet_id', $outlet->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['menuItems' => fn ($q) => $q->where('is_available', true)->orderBy('sort_order')->with('modifierGroups')])
            ->get();

        $uncategorized = MenuItem::where('outlet_id', $outlet->id)
            ->whereNull('category_id')
            ->where('is_available', true)
            ->with('modifierGroups')
            ->orderBy('sort_order')
            ->get();

        return compact('categories', 'uncategorized');
    }
}
