<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // 1. 修改字段类型
            $table->decimal('price_cents', 10, 2)->change();

            // 2. 重命名 price_cents → price
            $table->renameColumn('price_cents', 'price');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // 回滚：重命名回去 + 改回 unsignedInteger
            $table->renameColumn('price', 'price_cents');
            $table->unsignedInteger('price_cents')->change();
        });
    }
};
