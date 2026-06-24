<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    public const TYPE_SALE = 'sale';
    public const TYPE_RESTORE = 'restore';
    public const TYPE_ADJUST = 'adjust';

    protected $fillable = [
        'menu_item_id',
        'type',
        'quantity',
        'stock_after',
        'order_id',
        'user_id',
        'notes',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
