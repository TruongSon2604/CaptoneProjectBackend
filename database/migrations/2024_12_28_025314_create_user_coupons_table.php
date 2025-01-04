<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('user_id');  // ID của khách hàng
            // $table->unsignedBigInteger('coupon_id'); // ID của mã giảm giá
            $table->timestamp('applied_at')->nullable();// Thời gian áp dụng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_coupons');
    }
};
