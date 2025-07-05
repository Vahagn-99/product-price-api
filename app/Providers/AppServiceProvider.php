<?php

declare(strict_types=1);

namespace App\Providers;

use App\Base\Product\Currency\Convertor\SqlConvertor;
use App\Base\Product\Currency\Convertor\ISQLConvertor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register() : void
    {
        $this->app->singleton(ISQLConvertor::class, function () {
            return new SqlConvertor(config('app_currency.values', []));
        });

        $this->app->bind('currency', ISQLConvertor::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        //
    }
}
