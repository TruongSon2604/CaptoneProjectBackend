<?php

namespace App\Providers;

use App\Contracts\CategoryInterface;
use App\Contracts\StatusInterface;
use App\Models\Status;
use App\Repositories\CategoryRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
