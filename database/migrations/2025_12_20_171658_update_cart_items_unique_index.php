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

            // 1. 先删掉依赖 cart_id 上索引的外键
            $table->dropForeign('cart_items_cart_id_foreign');

            // 2. 再删掉旧的 unique(cart_id, product_id)
            $table->dropUnique('cart_items_cart_id_product_id_unique');

            // 3. 新建唯一索引：cart + product + variant 组合唯一
            $table->unique(
                ['cart_id', 'product_id', 'product_variant_id'],
                'cart_items_cart_id_product_id_variant_unique'
            );

            // 4. 把 cart_id 的外键再建回来（单列）
            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {

            // 回滚时按反顺序来
            $table->dropForeign('cart_items_cart_id_foreign');

            $table->dropUnique('cart_items_cart_id_product_id_variant_unique');

            // 还原旧索引
            $table->unique(
                ['cart_id', 'product_id'],
                'cart_items_cart_id_product_id_unique'
            );

            // 还原旧外键
            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
                ->onDelete('cascade');
        });
    }
};
