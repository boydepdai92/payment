<?php

namespace App\Providers;

use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;
use App\Services\Acquirers\AcquirerFactory;
use App\Services\Contracts\PaymentInterface;
use App\Services\Acquirers\Contracts\AcquirerFactoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentInterface::class, PaymentService::class);
        $this->app->bind(AcquirerFactoryInterface::class, AcquirerFactory::class);
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
