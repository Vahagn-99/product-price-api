<?php

namespace App\Providers;

use App\Base\Product\Cache\Listeners\ClearCache;
use Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::subscribe(ClearCache::class);
    }
}
