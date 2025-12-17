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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('order_no')->unique();

            // Shipping info (MVP)
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postcode');

            $table->unsignedInteger('subtotal_cents');
            $table->unsignedInteger('shipping_cents')->default(0);
            $table->unsignedInteger('total_cents');

            // Status (MVP)
            $table->string('status')->default('pending'); // pending, paid, processing, shipped, completed, cancelled

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
