<?php

namespace App\Providers;

use App\Contracts\AddressInterface;
use App\Contracts\CartInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\CommentInterface;
use App\Contracts\CouponInterface;
use App\Contracts\OrderInterface;
use App\Contracts\PaymentInterface;
use App\Contracts\PaymentMethodInterface;
use App\Contracts\ProductInterface;
use App\Contracts\StatusInterface;
use App\Contracts\UserCouponInterface;
use App\Models\Status;
use App\Repositories\AddressRepository;
use App\Repositories\CartRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CommentRepository;
use App\Repositories\CouponRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentMedthodRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StatusRepository;
use App\Repositories\UserCouponRepository;
use App\Services\PaymentMethodService;
use Illuminate\Support\ServiceProvider;
use PaymentRepository;

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
        $this->app->bind(CommentInterface::class,CommentRepository::class);
        $this->app->bind(CartInterface::class,CartRepository::class);
        $this->app->bind(OrderInterface::class,OrderRepository::class);
        $this->app->bind(PaymentInterface::class,PaymentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
