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
            // 对应 product_variants.id
            $table->foreignId('product_variant_id')
                ->nullable()
                ->after('unit_price') // ← 就加这里
                ->constrained('product_variants')
                ->nullOnDelete();

            // Variant label 文本
            $table->string('variant_label')
                ->nullable()
                ->after('product_variant_id'); // 放在 variant_id 后面
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_variant_id');
            $table->dropColumn('variant_label');
        });
    }
};
