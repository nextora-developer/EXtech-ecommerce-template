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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->unsignedInteger('qty')->default(1);
            $table->unsignedInteger('unit_price_cents'); // snapshot price at time of add

            $table->timestamps();

            $table->unique(['cart_id', 'product_id']); // prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
