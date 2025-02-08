<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa các mã giảm giá đã hết hạn';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCoupons = Coupon::where('end_date', '<', Carbon::today())->delete();
        $this->info("Đã xóa $expiredCoupons mã giảm giá hết hạn.");
    }
}
