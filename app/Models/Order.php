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
        'customer_email',

        'address_line1',
        'address_line2',
        'city',
        'state',
        'postcode',
        'country',

        'subtotal',
        'shipping_fee',
        'shipping_courier',
        'tracking_number',
        'shipped_at',
        'total',
        'status',

        'payment_method_code',
        'payment_method_name',
        'payment_receipt_path',

        'payment_status',       // ex: pending / paid / failed
        'payment_reference',    // HitPay payment_id
        'gateway',

        'remark',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
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
