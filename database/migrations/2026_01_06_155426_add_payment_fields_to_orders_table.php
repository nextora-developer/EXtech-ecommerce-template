<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // 支付状态（与 gateway 同步，例如 completed / failed / pending）
            $table->string('payment_status')
                ->nullable()
                ->after('status');

            // HitPay payment_id（对账用）
            $table->string('payment_reference')
                ->nullable()
                ->after('payment_status');

            // 支付网关，例如 hitpay / manual / bank_transfer
            $table->string('gateway')
                ->nullable()
                ->after('payment_reference');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'payment_reference',
                'gateway',
            ]);
        });
    }
};
