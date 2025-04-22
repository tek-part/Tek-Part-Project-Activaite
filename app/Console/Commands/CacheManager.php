<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Cache\CacheManager as LaravelCacheManager;
use Illuminate\Support\Facades\File;

class CacheManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:manage
                            {action? : The action to perform (clear/stats/optimize/warm)}
                            {--tag= : Clear cache only for specific tag}
                            {--force : Force the operation without asking for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application cache (clear, view stats, optimize, warm)';

    /**
     * The cache manager instance.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Cache\CacheManager  $cache
     * @return void
     */
    public function __construct(LaravelCacheManager $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action') ?? $this->choice(
            'What action would you like to perform?',
            ['clear', 'stats', 'optimize', 'warm'],
            0
        );

        switch ($action) {
            case 'clear':
                $this->clearCache();
                break;
            case 'stats':
                $this->showCacheStats();
                break;
            case 'optimize':
                $this->optimizeCache();
                break;
            case 'warm':
                $this->warmCache();
                break;
            default:
                $this->error('Invalid action specified.');
                return 1;
        }

        return 0;
    }

    /**
     * Clear the application cache.
     */
    protected function clearCache()
    {
        if ($tag = $this->option('tag')) {
            $this->clearTaggedCache($tag);
            return;
        }

        if (!$this->option('force') && !$this->confirm('Are you sure you want to clear all application cache?', true)) {
            $this->info('Cache clear operation cancelled.');
            return;
        }

        $this->info('Clearing application cache...');

        $this->callSilently('cache:clear');
        $this->info('✓ Application cache cleared.');

        $this->callSilently('config:clear');
        $this->info('✓ Configuration cache cleared.');

        $this->callSilently('route:clear');
        $this->info('✓ Route cache cleared.');

        $this->callSilently('view:clear');
        $this->info('✓ Compiled views cleared.');

        // Clear Laravel's compiled classes
        if (file_exists(base_path('bootstrap/cache/compiled.php'))) {
            File::delete(base_path('bootstrap/cache/compiled.php'));
            $this->info('✓ Compiled class file removed.');
        }

        // Clear application file/folder cache
        if (file_exists(storage_path('framework/cache/data'))) {
            $this->callSilently('cache:clear');
            $this->info('✓ Framework cache cleared.');
        }

        $this->info('All cache stores have been cleared successfully!');
    }

    /**
     * Clear cache for a specific tag.
     */
    protected function clearTaggedCache($tag)
    {
        try {
            // Check if the current cache driver supports tags
            if (method_exists($this->cache->store(), 'tags')) {
                $this->cache->tags($tag)->flush();
                $this->info("Cache for tag '{$tag}' cleared successfully.");
            } else {
                $this->error('Your current cache driver does not support tags.');
                $this->info('Please set CACHE_SUPPORTS_TAGS=true in your .env file and use a cache driver that supports tags like Redis or Memcached.');
            }
        } catch (\Exception $e) {
            $this->error("Failed to clear tagged cache: {$e->getMessage()}");
        }
    }

    /**
     * Display cache statistics.
     */
    protected function showCacheStats()
    {
        $this->info('Cache Statistics:');
        $driver = config('cache.default');
        $this->info('Current Cache Driver: ' . $driver);

        // Get statistics based on the cache driver
        switch ($driver) {
            case 'file':
                $this->showFileCacheStats();
                break;
            case 'redis':
                $this->showRedisCacheStats();
                break;
            case 'memcached':
                $this->showMemcachedCacheStats();
                break;
            default:
                $this->info('Cache statistics not available for this driver.');
                break;
        }
    }

    /**
     * Show file cache statistics.
     */
    protected function showFileCacheStats()
    {
        $cachePath = storage_path('framework/cache/data');

        if (!File::exists($cachePath)) {
            $this->info('No cache files found.');
            return;
        }

        $files = File::allFiles($cachePath);
        $totalSize = 0;

        foreach ($files as $file) {
            $totalSize += $file->getSize();
        }

        $this->table(
            ['Metric', 'Value'],
            [
                ['Cache Files', count($files)],
                ['Total Size', $this->formatSize($totalSize)],
                ['Cache Path', $cachePath],
            ]
        );
    }

    /**
     * Show Redis cache statistics.
     */
    protected function showRedisCacheStats()
    {
        try {
            $redis = app('redis');
            $info = $redis->info();

            $this->table(
                ['Metric', 'Value'],
                [
                    ['Used Memory', $this->formatSize($info['used_memory'] ?? 0)],
                    ['Peak Memory', $this->formatSize($info['used_memory_peak'] ?? 0)],
                    ['Connected Clients', $info['connected_clients'] ?? 0],
                    ['Total Commands Processed', $info['total_commands_processed'] ?? 0],
                    ['Redis Version', $info['redis_version'] ?? 'Unknown'],
                ]
            );
        } catch (\Exception $e) {
            $this->error('Unable to connect to Redis server: ' . $e->getMessage());
        }
    }

    /**
     * Show Memcached cache statistics.
     */
    protected function showMemcachedCacheStats()
    {
        try {
            $memcached = app('memcached.connector')->getMemcached();
            $stats = $memcached->getStats();

            if (empty($stats)) {
                $this->info('No Memcached statistics available.');
                return;
            }

            $serverStats = array_shift($stats);

            $this->table(
                ['Metric', 'Value'],
                [
                    ['Current Items', $serverStats['curr_items'] ?? 0],
                    ['Total Items', $serverStats['total_items'] ?? 0],
                    ['Bytes Used', $this->formatSize($serverStats['bytes'] ?? 0)],
                    ['Get Hits', $serverStats['get_hits'] ?? 0],
                    ['Get Misses', $serverStats['get_misses'] ?? 0],
                    ['Uptime', $this->formatUptime($serverStats['uptime'] ?? 0)],
                ]
            );
        } catch (\Exception $e) {
            $this->error('Unable to connect to Memcached server: ' . $e->getMessage());
        }
    }

    /**
     * Optimize the application cache.
     */
    protected function optimizeCache()
    {
        if (!$this->option('force') && !$this->confirm('Are you sure you want to optimize application cache?', true)) {
            $this->info('Cache optimization cancelled.');
            return;
        }

        $this->info('Optimizing application cache...');

        // Clear existing cache first
        $this->callSilently('cache:clear');
        $this->callSilently('config:clear');
        $this->callSilently('route:clear');

        // Cache configuration
        $this->callSilently('config:cache');
        $this->info('✓ Configuration cached.');

        // Cache routes
        $this->callSilently('route:cache');
        $this->info('✓ Routes cached.');

        // Optimize autoloader
        $this->callSilently('optimize:clear');
        $this->callSilently('optimize');
        $this->info('✓ Optimized class loader.');

        $this->info('Application cache optimization complete!');
    }

    /**
     * Warm the application cache with frequently used data.
     */
    protected function warmCache()
    {
        $this->info('Warming up application cache...');

        // Read the list of keys to warm from config
        $keysToWarm = config('cache.warm_cache_keys', []);

        if (empty($keysToWarm)) {
            $this->info('No cache keys defined for warming. Add keys to the cache.warm_cache_keys configuration.');
            return;
        }

        $this->info('Warming up ' . count($keysToWarm) . ' cache keys...');

        // This is a stub - you would implement actual warming logic here
        // based on your application's needs
        $this->warn('Cache warming requires custom implementation for your application.');
        $this->info('You need to implement the cache warming logic in the CacheManager or in a service class.');

        $this->info('Example implementation:');
        $this->line('foreach ($keysToWarm as $key) {');
        $this->line('    switch ($key) {');
        $this->line('        case "app_settings":');
        $this->line('            $settings = Settings::all();');
        $this->line('            cache()->put("app_settings", $settings, now()->addDay());');
        $this->line('            break;');
        $this->line('    }');
        $this->line('}');

        $this->info('Cache warming simulated successfully!');
    }

    /**
     * Format file size to human-readable format.
     */
    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Format uptime in seconds to a human-readable format.
     */
    private function formatUptime($seconds)
    {
        $days = floor($seconds / 86400);
        $seconds %= 86400;

        $hours = floor($seconds / 3600);
        $seconds %= 3600;

        $minutes = floor($seconds / 60);
        $seconds %= 60;

        $result = '';
        if ($days > 0) {
            $result .= $days . 'd ';
        }

        return $result . sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
