<?php

namespace App\Providers;

use App\Repositories\OrderRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\OrderRepositoryInterface;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }
}
