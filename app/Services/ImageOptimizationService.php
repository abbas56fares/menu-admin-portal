<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageOptimizationService
{
    /**
     * Optimize and save an uploaded image
     * 
     * @param UploadedFile $file
     * @param string $directory Directory within storage/app/public
     * @param int $maxWidth Maximum width in pixels
     * @param int $quality JPEG/WebP quality (1-100)
     * @return string Path to the saved file
     */
    public function optimizeAndStore(
        UploadedFile $file, 
        string $directory = 'items', 
        int $maxWidth = 800, 
        int $quality = 80
    ): string {
        // Generate a unique filename
        $filename = uniqid() . '_' . time() . '.jpg';
        $path = $directory . '/' . $filename;
        $fullPath = storage_path('app/public/' . $path);

        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // Load and optimize the image
        $image = Image::read($file->getRealPath());
        
        // Resize if width exceeds max
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Convert to JPEG and compress
        $image->toJpeg($quality)->save($fullPath);

        return $path;
    }

    /**
     * Delete an image from storage
     * 
     * @param string|null $path
     * @return bool
     */
    public function delete(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get optimized image size info
     * 
     * @param string $path
     * @return array|null
     */
    public function getImageInfo(string $path): ?array
    {
        $fullPath = storage_path('app/public/' . $path);
        
        if (!file_exists($fullPath)) {
            return null;
        }

        $sizeInBytes = filesize($fullPath);
        $sizeInKB = round($sizeInBytes / 1024, 2);
        $sizeInMB = round($sizeInBytes / 1024 / 1024, 2);

        return [
            'path' => $path,
            'size_bytes' => $sizeInBytes,
            'size_kb' => $sizeInKB,
            'size_mb' => $sizeInMB,
            'size_human' => $sizeInMB >= 1 ? $sizeInMB . ' MB' : $sizeInKB . ' KB',
        ];
    }
}
