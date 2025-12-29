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
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variant_id')
                ->nullable()
                ->after('product_id');

            $table->string('variant_label')
                ->nullable()
                ->after('product_variant_id');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // 记得先 drop 外键再 drop column（如果有加的话）
            // $table->dropForeign(['product_variant_id']);

            $table->dropColumn(['product_variant_id', 'variant_label']);
        });
    }
};
