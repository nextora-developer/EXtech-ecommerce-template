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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // 显示用名称，例如：Online Transfer
            $table->string('code')->unique();     // 程式用 code，例如：online_transfer, toyyibpay, stripe
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);

            // 只对 bank transfer 有用的字段
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('duitnow_qr_path')->nullable(); // 存 storage 路径

            $table->text('instructions')->nullable(); // 额外说明

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
