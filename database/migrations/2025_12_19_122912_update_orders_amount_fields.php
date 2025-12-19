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

            // rename fields
            $table->renameColumn('subtotal_cents', 'subtotal');
            $table->renameColumn('shipping_cents', 'shipping_fee');
            $table->renameColumn('total_cents', 'total');
        });

        Schema::table('orders', function (Blueprint $table) {
            // change column type after rename
            $table->decimal('subtotal', 10, 2)->change();
            $table->decimal('shipping_fee', 10, 2)->default(0)->change();
            $table->decimal('total', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('subtotal', 'subtotal_cents');
            $table->renameColumn('shipping_fee', 'shipping_cents');
            $table->renameColumn('total', 'total_cents');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('subtotal_cents')->change();
            $table->unsignedInteger('shipping_cents')->change();
            $table->unsignedInteger('total_cents')->change();
        });
    }
};
