<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CacheController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings', ['only' => ['index', 'clear', 'optimize']]);
    }

    /**
     * Display cache management page
     */
    public function index()
    {
        $cacheStats = [
            'driver' => config('cache.default'),
            'ttl' => config('cache.ttl', config('session.lifetime')) . ' ' . __('translations.minutes'),
            'prefix' => config('cache.prefix'),
            'file_count' => 0,
            'total_size' => '0 KB'
        ];

        // Get file cache stats if using file driver
        if ($cacheStats['driver'] === 'file') {
            $cachePath = storage_path('framework/cache/data');
            $files = is_dir($cachePath) ? glob($cachePath . '/*') : [];
            $cacheStats['file_count'] = count($files);

            $totalSize = 0;
            foreach ($files as $file) {
                if (is_file($file)) {
                    $totalSize += filesize($file);
                }
            }

            $cacheStats['total_size'] = $this->formatBytes($totalSize);
        }

        return view('admin.settings.cache', compact('cacheStats'));
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Clear application cache
     */
    public function clear()
    {
        try {
            // Clear various caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            Log::info('Application cache cleared');

            return redirect()->route('admin.settings.cache')->with('success', __('translations.cache-cleared-success'));
        } catch (\Exception $e) {
            Log::error('Cache clearing failed: ' . $e->getMessage());
            return redirect()->route('admin.settings.cache')->with('error', $e->getMessage());
        }
    }

    /**
     * Optimize application
     */
    public function optimize()
    {
        try {
            // Optimize the application
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('optimize');

            Log::info('Application optimized');

            return redirect()->route('admin.settings.cache')->with('success', __('translations.cache-optimized-success'));
        } catch (\Exception $e) {
            Log::error('Optimization failed: ' . $e->getMessage());
            return redirect()->route('admin.settings.cache')->with('error', $e->getMessage());
        }
    }
}
