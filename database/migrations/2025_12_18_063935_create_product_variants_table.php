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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('sku')->nullable();    // 每个变体自己的 SKU

            // 用 JSON 存所有选项组合，比如：
            // {"Size": "M", "Color": "Black"}
            $table->json('options')->nullable();

            $table->decimal('price', 10, 2)->nullable(); // 可以 override 主产品价格
            $table->integer('stock')->default(0);

            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // 组合唯一（防止同样组合重复）
            $table->unique(['product_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
