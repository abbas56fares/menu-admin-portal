<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;

class OptimizeExistingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize {--dry-run : Show what would be optimized without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize all existing item images to reduce file sizes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $imageService = new ImageOptimizationService();
        
        $this->info('Starting image optimization...');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No files will be modified');
        }
        
        $items = Item::whereNotNull('image')->get();
        $totalItems = $items->count();
        
        if ($totalItems === 0) {
            $this->info('No items with images found.');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$totalItems} items with images.");
        $this->newLine();
        
        $bar = $this->output->createProgressBar($totalItems);
        $bar->start();
        
        $optimized = 0;
        $skipped = 0;
        $errors = 0;
        $totalSizeBefore = 0;
        $totalSizeAfter = 0;
        
        foreach ($items as $item) {
            $bar->advance();
            
            $imagePath = $item->image;
            $fullPath = storage_path('app/public/' . $imagePath);
            
            if (!file_exists($fullPath)) {
                $skipped++;
                continue;
            }
            
            try {
                $sizeBefore = filesize($fullPath);
                $totalSizeBefore += $sizeBefore;
                
                if (!$dryRun) {
                    // Create a temporary backup
                    $backupPath = $fullPath . '.backup';
                    copy($fullPath, $backupPath);
                    
                    // Optimize the image
                    $imageService->optimizeAndStore(
                        new \Illuminate\Http\UploadedFile($fullPath, basename($fullPath)),
                        dirname($imagePath),
                        800,
                        80
                    );
                    
                    // Delete backup if successful
                    @unlink($backupPath);
                }
                
                clearstatcache(true, $fullPath);
                $sizeAfter = $dryRun ? $sizeBefore * 0.2 : filesize($fullPath); // Estimate 80% reduction
                $totalSizeAfter += $sizeAfter;
                
                $optimized++;
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error optimizing {$imagePath}: " . $e->getMessage());
            }
        }
        
        $bar->finish();
        $this->newLine(2);
        
        // Results
        $this->info('=== Optimization Results ===');
        $this->info("Optimized: {$optimized}");
        $this->warn("Skipped (not found): {$skipped}");
        
        if ($errors > 0) {
            $this->error("Errors: {$errors}");
        }
        
        $savedBytes = $totalSizeBefore - $totalSizeAfter;
        $savedMB = round($savedBytes / 1024 / 1024, 2);
        $savedPercent = $totalSizeBefore > 0 ? round(($savedBytes / $totalSizeBefore) * 100, 1) : 0;
        
        $this->newLine();
        $this->info("Total size before: " . round($totalSizeBefore / 1024 / 1024, 2) . " MB");
        $this->info("Total size after: " . round($totalSizeAfter / 1024 / 1024, 2) . " MB");
        $this->info("Space saved: {$savedMB} MB ({$savedPercent}%)");
        
        if ($dryRun) {
            $this->newLine();
            $this->warn('This was a DRY RUN. Run without --dry-run to actually optimize images.');
        } else {
            $this->newLine();
            $this->info('✅ Optimization complete! Don\'t forget to clear cache:');
            $this->comment('php artisan cache:clear-menu');
        }
        
        return Command::SUCCESS;
    }
}
