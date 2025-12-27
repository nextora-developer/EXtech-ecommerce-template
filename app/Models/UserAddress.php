<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postcode',
        'country',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
