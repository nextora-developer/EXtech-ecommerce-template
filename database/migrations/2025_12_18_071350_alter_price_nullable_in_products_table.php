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
        Schema::table('products', function (Blueprint $table) {
            // 把 price 改成可以為 null
            $table->decimal('price', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // 回滚时改回不允许 null，你之前如果是 default(0) 可以一起加回去
            $table->decimal('price', 10, 2)->nullable(false)->default(0)->change();
        });
    }
};
