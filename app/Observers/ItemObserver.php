<?php

namespace App\Observers;

use App\Models\Item;
use Illuminate\Support\Facades\Cache;

class ItemObserver
{
    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        $this->clearCache($item);
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        $this->clearCache($item);
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        $this->clearCache($item);
    }

    /**
     * Clear relevant cache when item changes
     */
    protected function clearCache(Item $item): void
    {
        // Clear all menu-related cache
        Cache::flush();
    }
}
