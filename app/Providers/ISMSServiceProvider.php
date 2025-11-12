<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Policy\Policy;
use App\Observers\PolicyObserver;

class ISMSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Policy::observe(PolicyObserver::class);
    }
}