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
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                // $table->bigInteger('user_id');
                $table->string('order_number')->unique();
                // $table->unsignedBigInteger('coupon_id')->nullable();  // Thêm cột lưu mã giảm giá
                $table->decimal('discount_amount', 10, 2)->default(0); // Số tiền giảm giá
                $table->decimal('final_amount', 10, 2); // Tổng tiền sau giảm giá
                $table->decimal('latitude', 10, 6)->nullable();  // Vĩ độ của khách hàng
                $table->decimal('longitude', 10, 6)->nullable();  // Kinh độ của khách hàng
                // $table->bigInteger('address_id');
                $table->enum('status', ['pending', 'in_progress', 'completed', 'canceled'])->default('pending');
                $table->decimal('total_amount', 10, 2);  // Tổng số tiền
                $table->decimal('shipping_fee', 10, 2);  // Phí vận chuyển
                $table->string('transaction_id')->nullable();
                $table->enum('status_payment', ['pending', 'paid', 'failed'])->default('pending');
                // $table->dateTime('assigned_shipped_at')->nullable();  // Thời gian shipper nhận đơn
                // $table->dateTime('delivered_at')->nullable();  // Thời gian giao hàng
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
