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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. west_my, east_my, digital
            $table->string('name');           // "West Malaysia", "East Malaysia", "Digital Product"
            $table->decimal('rate', 10, 2)->default(0); // 平邮一口价
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
