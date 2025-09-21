<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PaymentService::class, function ($app) {
            return new PaymentService();
        });
        
    }

    public function boot()
    {
        Paginator::useTailwind();
    
    
}
}