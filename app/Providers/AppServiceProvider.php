<?php

namespace App\Providers;

use App\Contracts\AddressInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\CouponInterface;
use App\Contracts\PaymentMethodInterface;
use App\Contracts\ProductInterface;
use App\Contracts\StatusInterface;
use App\Contracts\UserCouponInterface;
use App\Models\Status;
use App\Repositories\AddressRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CouponRepository;
use App\Repositories\PaymentMedthodRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StatusRepository;
use App\Repositories\UserCouponRepository;
use App\Services\PaymentMethodService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(StatusInterface::class,StatusRepository::class);
        $this->app->bind(ProductInterface::class,ProductRepository::class);
        $this->app->bind(AddressInterface::class,AddressRepository::class);
        $this->app->bind(CouponInterface::class,CouponRepository::class);
        $this->app->bind(UserCouponInterface::class,UserCouponRepository::class);
        $this->app->bind(PaymentMethodInterface::class,PaymentMedthodRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
