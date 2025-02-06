<?php

namespace App\Providers;

use App\Contracts\AddressesInterface;
use App\Contracts\CategoryInterface;
use App\Contracts\ProductInterface;
use App\Contracts\StatusInterface;
use App\Models\Address;
use App\Models\Status;
use App\Repositories\AddressRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\StatusRepository;
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
        $this->app->bind(AddressesInterface::class,AddressRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
