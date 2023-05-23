<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if(request()->is('owner*')){
            config(['session.cookie' => config('session.cookie_owner')]);
        }

        if(request()->is('admin*')){
            config(['session.cookie' => config('session.cookie_admin')]);
        }
    }
}
