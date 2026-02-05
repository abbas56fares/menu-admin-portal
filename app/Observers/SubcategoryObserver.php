<?php

namespace App\Observers;

use App\Models\Subcategory;
use Illuminate\Support\Facades\Cache;

class SubcategoryObserver
{
    /**
     * Handle the Subcategory "created" event.
     */
    public function created(Subcategory $subcategory): void
    {
        Cache::flush();
    }

    /**
     * Handle the Subcategory "updated" event.
     */
    public function updated(Subcategory $subcategory): void
    {
        Cache::flush();
    }

    /**
     * Handle the Subcategory "deleted" event.
     */
    public function deleted(Subcategory $subcategory): void
    {
        Cache::flush();
    }
}
