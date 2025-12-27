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
            $table->string('payment_method_code')->nullable()->after('status');
            $table->string('payment_method_name')->nullable()->after('payment_method_code');
            $table->string('payment_receipt_path')->nullable()->after('payment_method_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method_code',
                'payment_method_name',
                'payment_receipt_path',
            ]);
        });
    }
};
