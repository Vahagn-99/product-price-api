<?php

namespace App\Providers;

use App\Models\Product as ProductModel;
use App\Repository\Query;
use App\Base\Product\Repository\{
    CacheDecorator,
    IProductRepository,
    ProductRepository,
};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register() : void
    {
        $this->app->bind(IProductRepository::class, function () {
           return new CacheDecorator(new ProductRepository(new Query(ProductModel::class)));
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
