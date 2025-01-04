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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();  // Mã giảm giá (ví dụ: "DISCOUNT20")
            $table->enum('discount_type', ['percentage', 'fixed']);  // Loại giảm giá (Phần trăm hoặc cố định)
            $table->decimal('discount_value', 10, 2);  // Giá trị giảm giá (phần trăm hoặc số tiền cố định)
            $table->date('start_date');  // Ngày bắt đầu có hiệu lực
            $table->date('end_date');  // Ngày hết hạn
            $table->boolean('is_active')->default(true);  // Trạng thái hoạt động của mã giảm giá
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
