<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_no',

        'customer_name',
        'customer_phone',
        'customer_email',          // 🆕 新增

        'address_line1',
        'address_line2',
        'city',
        'state',
        'postcode',
        'country',                 // 🆕 新增

        'subtotal',
        'shipping_fee',
        'total',
        'status',

        'payment_method_code',     // 🆕 新增
        'payment_method_name',     // 🆕 新增
        'payment_receipt_path',    // 🆕 新增
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
