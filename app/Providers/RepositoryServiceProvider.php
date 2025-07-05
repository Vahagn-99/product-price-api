<?php

namespace App\Providers;

use App\Base\Product\Repositories\{
    CachedProductRepository,
    IProductRepository,
    ProductRepository,
};
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register() : void
    {
        $this->app->bind(IProductRepository::class, function (Application $app) {
           return $app->make(CachedProductRepository::class, ['actual_product_repository' => $app->make(ProductRepository::class)]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot() : void
    {
        //
    }
}
