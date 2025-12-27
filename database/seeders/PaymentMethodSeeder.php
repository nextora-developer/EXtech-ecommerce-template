<?php

// database/seeders/PaymentMethodSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        PaymentMethod::updateOrCreate(
            ['code' => 'online_transfer'],
            [
                'name'                 => 'Online Transfer / Bank Transfer',
                'is_active'            => true,
                'is_default'           => true,
                'bank_name'            => 'Maybank',
                'bank_account_name'    => 'BRIF Commerce Sdn Bhd',
                'bank_account_number'  => '1234567890',
                'duitnow_qr_path'      => null, // 之后 admin 上传 QR 图片更新
                'instructions'         => 'Please transfer the total amount and upload your payment receipt.',
            ]
        );
    }
}
