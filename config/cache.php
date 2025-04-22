<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    */

    'default' => env('CACHE_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "apc", "array", "database", "file",
    |         "memcached", "redis", "dynamodb", "octane", "null"
    |
    */

    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
            'lock_connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
            'permission' => 0775,
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
                // Memcached::OPT_DISTRIBUTION => Memcached::DISTRIBUTION_CONSISTENT,
                // Memcached::OPT_SERVER_FAILURE_LIMIT => 2,
                // Memcached::OPT_REMOVE_FAILED_SERVERS => true,
                // Memcached::OPT_RETRY_TIMEOUT => 1,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
            'lock_connection' => 'default',
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        'octane' => [
            'driver' => 'octane',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing the APC, database, memcached, Redis, or DynamoDB cache
    | stores there might be other applications using the same cache. For
    | that reason, you may prefix every cache key to avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'),

    /*
    |--------------------------------------------------------------------------
    | Cache Lifetime Management
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default cache lifetimes for your application.
    | These values will be used when the 'ttl' option is not explicitly set.
    | A value of 0 means the data will be stored until manually removed.
    |
    */

    'ttl' => env('CACHE_TTL', 3600), // Default 1 hour (in seconds)

    /*
    |--------------------------------------------------------------------------
    | Cache Garbage Collection
    |--------------------------------------------------------------------------
    |
    | These settings control how often expired cache items are removed.
    | The gc_probability compared to gc_divisor is used to manage
    | how frequently the garbage collection process runs.
    |
    */

    'gc_probability' => env('CACHE_GC_PROBABILITY', 1),
    'gc_divisor' => env('CACHE_GC_DIVISOR', 100),

    /*
    |--------------------------------------------------------------------------
    | Cache Tags Management
    |--------------------------------------------------------------------------
    |
    | This controls whether tag functionality is enabled for drivers
    | that support it. Tag functionality allows you to group items
    | and invalidate caches based on those tags.
    |
    */

    'supports_tags' => env('CACHE_SUPPORTS_TAGS', false),

    /*
    |--------------------------------------------------------------------------
    | Cache Warming
    |--------------------------------------------------------------------------
    |
    | If you want to pre-populate certain cache keys on application startup,
    | you can define them here. This is useful for high-traffic applications
    | where you want critical data to already be cached.
    |
    */

    'warm_cache_keys' => [
        // List keys you want pre-populated on application startup
        // e.g., 'app_settings', 'global_config'
    ],

];
