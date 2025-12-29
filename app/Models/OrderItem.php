<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'qty',
        'unit_price',
        'product_variant_id',   // ⭐ 新增
        'variant_label',        // ⭐ 新增
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // 可选：给 Blade 用 $item->subtotal
    public function getSubtotalAttribute()
    {
        return $this->qty * $this->unit_price;
    }
}
