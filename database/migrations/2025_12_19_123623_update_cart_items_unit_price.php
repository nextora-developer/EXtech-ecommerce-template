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
        Schema::table('cart_items', function (Blueprint $table) {
            // rename column
            $table->renameColumn('unit_price_cents', 'unit_price');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            // change type to decimal
            $table->decimal('unit_price', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // revert name
            $table->renameColumn('unit_price', 'unit_price_cents');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            // revert type
            $table->unsignedInteger('unit_price_cents')->change();
        });
    }
};
