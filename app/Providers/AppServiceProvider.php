<?php

namespace App\Providers;

use App\Interface\Orders\OrderRepositoryInterface;
use App\Interface\Products\ProductRepositoryInterface;
use App\Repository\Orders\OrderRepository;
use App\Repository\Products\ProductRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class,ProductRepository ::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
