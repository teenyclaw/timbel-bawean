<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiningTableController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\LoyaltyController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\MenuCopyController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ModifierController;
use App\Http\Controllers\Admin\OrderHistoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ShiftHistoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Customer\CartController as CustomerCartController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\TableCartController;
use App\Http\Controllers\Customer\QrisController;
use App\Http\Controllers\Customer\TableController as CustomerTableController;
use App\Http\Controllers\Kitchen\KitchenDisplayController;
use App\Http\Controllers\OutletSwitchController;
use App\Http\Controllers\Pos\PaymentController;
use App\Http\Controllers\Pos\QueueController;
use App\Http\Controllers\Pos\QrisPaymentController;
use App\Http\Controllers\Pos\ShiftController;
use App\Http\Controllers\Pos\ReceiptController;
use App\Http\Controllers\Pos\TableController as PosTableController;
use App\Http\Controllers\Webhook\MidtransWebhookController;

Route::post('/webhooks/midtrans', MidtransWebhookController::class)->name('webhooks.midtrans');

Route::get('/', fn () => redirect()->route('login'));

Route::prefix('o/{slug}')->name('customer.')->group(function () {
    Route::get('/', [CatalogController::class, 'show'])->name('catalog');
    Route::get('/cart', [CustomerCartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CustomerCartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{lineKey}', [CustomerCartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{lineKey}', [CustomerCartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::get('/checkout/loyalty', [CheckoutController::class, 'loyaltyLookup'])->name('checkout.loyalty');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/thanks/{orderNumber}', [CheckoutController::class, 'thanks'])->name('thanks');
    Route::get('/pay/{orderNumber}', [QrisController::class, 'show'])->name('qris');
    Route::get('/pay/{orderNumber}/{payment}/status', [QrisController::class, 'status'])->name('qris.status');

    Route::prefix('t/{token}')->name('table.')->group(function () {
        Route::get('/', [CustomerTableController::class, 'enter'])->name('enter');
        Route::get('/menu', [CustomerTableController::class, 'catalog'])->name('catalog');
        Route::get('/bill', [CustomerTableController::class, 'bill'])->name('bill');
        Route::post('/order', [CustomerTableController::class, 'submitOrder'])->name('order.submit');
        Route::get('/cart', [TableCartController::class, 'index'])->name('cart');
        Route::post('/cart/add', [TableCartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/{lineKey}', [TableCartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{lineKey}', [TableCartController::class, 'remove'])->name('cart.remove');
    });
});

Route::middleware(['auth', 'outlet'])->group(function () {
    Route::post('/outlet/switch', [OutletSwitchController::class, 'store'])->name('outlet.switch');

    Route::get('/dashboard', DashboardController::class)
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [QueueController::class, 'index'])->name('queue');
        Route::get('/poll', [QueueController::class, 'poll'])->name('poll');
        Route::get('/tables', [PosTableController::class, 'index'])->name('tables.index');
        Route::post('/tables/{table}/open', [PosTableController::class, 'open'])->name('tables.open');
        Route::get('/tables/orders/{order}', [PosTableController::class, 'order'])->name('tables.order');
        Route::post('/tables/orders/{order}/items', [PosTableController::class, 'addItem'])->name('tables.items.add');
        Route::patch('/tables/orders/{order}/items/{item}', [PosTableController::class, 'updateItem'])->name('tables.items.update');
        Route::delete('/tables/orders/{order}/items/{item}', [PosTableController::class, 'removeItem'])->name('tables.items.remove');
        Route::post('/tables/orders/{order}/submit', [PosTableController::class, 'submit'])->name('tables.submit');
        Route::post('/tables/orders/{order}/close', [PosTableController::class, 'close'])->name('tables.close');
        Route::get('/orders/{order}', [QueueController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/confirm', [QueueController::class, 'confirm'])->name('orders.confirm');
        Route::post('/orders/{order}/complete', [QueueController::class, 'complete'])->name('orders.complete');
        Route::post('/orders/{order}/cancel', [QueueController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{order}/payment', [PaymentController::class, 'show'])->name('payment');
        Route::post('/orders/{order}/payment', [PaymentController::class, 'store'])->name('payment.store');
        Route::get('/orders/{order}/qris/{payment}', [QrisPaymentController::class, 'show'])->name('qris.show');
        Route::get('/orders/{order}/qris/{payment}/status', [QrisPaymentController::class, 'status'])->name('qris.status');
        Route::get('/orders/{order}/receipt/{payment?}', [ReceiptController::class, 'show'])->name('receipt');

        Route::get('/shift', [ShiftController::class, 'show'])->name('shift.show');
        Route::post('/shift/open', [ShiftController::class, 'open'])->name('shift.open');
        Route::post('/shift/{shift}/close', [ShiftController::class, 'close'])->name('shift.close');
    });

    Route::prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/', [KitchenDisplayController::class, 'index'])->name('display');
        Route::get('/poll', [KitchenDisplayController::class, 'poll'])->name('poll');
        Route::post('/orders/{order}/start', [KitchenDisplayController::class, 'startCooking'])->name('orders.start');
        Route::post('/orders/{order}/ready', [KitchenDisplayController::class, 'markReady'])->name('orders.ready');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/menu-items', [MenuItemController::class, 'index'])->name('menu-items.index');
        Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
        Route::get('/menu-items/{menuItem}/edit', [MenuItemController::class, 'edit'])->name('menu-items.edit');
        Route::put('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
        Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');

        Route::get('/menu-copy', [MenuCopyController::class, 'index'])->name('menu-copy.index');
        Route::post('/menu-copy', [MenuCopyController::class, 'store'])->name('menu-copy.store');

        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory/{menuItem}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

        Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty.index');
        Route::put('/loyalty/settings', [LoyaltyController::class, 'updateSettings'])->name('loyalty.settings');
        Route::post('/loyalty/members/{member}/adjust', [LoyaltyController::class, 'adjustMember'])->name('loyalty.members.adjust');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::put('/notifications', [NotificationController::class, 'update'])->name('notifications.update');
        Route::post('/notifications/test', [NotificationController::class, 'test'])->name('notifications.test');

        Route::post('/menu-items/{menuItem}/modifier-groups', [ModifierController::class, 'storeGroup'])->name('modifier-groups.store');
        Route::delete('/modifier-groups/{group}', [ModifierController::class, 'destroyGroup'])->name('modifier-groups.destroy');
        Route::post('/modifier-groups/{group}/options', [ModifierController::class, 'storeOption'])->name('modifier-options.store');
        Route::delete('/modifier-options/{option}', [ModifierController::class, 'destroyOption'])->name('modifier-options.destroy');

        Route::get('/outlets', [OutletController::class, 'index'])->name('outlets.index');
        Route::get('/outlets/create', [OutletController::class, 'create'])->name('outlets.create');
        Route::post('/outlets', [OutletController::class, 'store'])->name('outlets.store');
        Route::get('/outlets/{outlet}/edit', [OutletController::class, 'edit'])->name('outlets.edit');
        Route::put('/outlets/{outlet}', [OutletController::class, 'update'])->name('outlets.update');
        Route::delete('/outlets/{outlet}', [OutletController::class, 'destroy'])->name('outlets.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::get('/tables', [DiningTableController::class, 'index'])->name('tables.index');
        Route::post('/tables', [DiningTableController::class, 'store'])->name('tables.store');
        Route::put('/tables/{table}', [DiningTableController::class, 'update'])->name('tables.update');
        Route::delete('/tables/{table}', [DiningTableController::class, 'destroy'])->name('tables.destroy');
        Route::post('/tables/{table}/regenerate-token', [DiningTableController::class, 'regenerateToken'])->name('tables.regenerate-token');

        Route::get('/orders', [OrderHistoryController::class, 'index'])->name('orders.index');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        Route::get('/shifts', [ShiftHistoryController::class, 'index'])->name('shifts.index');
        Route::get('/shifts/{shift}', [ShiftHistoryController::class, 'show'])->name('shifts.show');
    });
});

require __DIR__.'/auth.php';
