<?php

namespace App\Base\Product\Cache\Listeners;

use App\Base\Product\Cache\Keys;
use App\Base\Product\Events\{
    Created as CreatedEvent,
    Deleted as DeletedEvent,
    Updated as UpdatedEvent,
};
use Cache;
use Illuminate\Events\Dispatcher;

class ClearCache
{
    /**
     * Очистка кэша при удал
     *
     * @param \App\Base\Product\Events\Created $event
     * @return void
     */
    public function handleCreated(CreatedEvent $event): void
    {
        $this->clearGetAllConvertedCache();
    }

    /**
     * Очистка кэша при обновлении
     *
     * @param \App\Base\Product\Events\Updated $event
     * @return void
     */
    public function handleUpdated(UpdatedEvent $event): void
    {
        $this->clearGetAllConvertedCache();
    }

    /**
     * Очистка кэша при удалении
     *
     * @param \App\Base\Product\Events\Deleted $event
     * @return void
     */
    public function handleDeleted(DeletedEvent $event): void
    {
        $this->clearGetAllConvertedCache();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            CreatedEvent::class => "handleCreated",
            UpdatedEvent::class => "handleUpdated",
            DeletedEvent::class => "handleDeleted",
        ];
    }

    //****************************************************************
    //************************** Support *****************************
    //****************************************************************

    /**
     * Очистка кэша при обновлении списка продуктов
     *
     * @return void
     */
    private function clearGetAllConvertedCache(): void
    {
        Cache::tags(Keys::getAllConvertedTag())->flush();
    }
}
