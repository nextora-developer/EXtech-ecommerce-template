<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'short_description',
        'is_active',
        'is_default',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'duitnow_qr_path',
        'instructions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];
}
