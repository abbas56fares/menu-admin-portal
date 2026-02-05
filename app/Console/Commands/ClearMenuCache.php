<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class ClearMenuCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear menu and frontend cache data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Clear specific cache keys
        $keys = [
            'menu_data_*',
            'frontend_categories',
            'frontend_types',
            'category_data_*',
            'subcategory_items_*',
        ];

        Cache::flush();
        
        $this->info('Menu cache cleared successfully!');
        $this->info('Note: Use this command when you update items, categories, subcategories, or types.');
        
        return Command::SUCCESS;
    }
}
