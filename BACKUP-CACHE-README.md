# Backup and Cache Management

This document outlines how to use the backup and cache management features implemented in this Laravel application.

## Backup Management

The application uses the popular `spatie/laravel-backup` package to manage backups of both your database and files. The backups are compressed and stored in the `storage/app/Laravel` directory by default.

### Configuration

Backup settings are stored in `config/backup.php`. The main settings include:

- **What to backup**: Both files and database are backed up by default
- **Excluded directories**: `vendor`, `node_modules`, `.git`, and cache folders are excluded to keep backups small
- **Database compression**: Uses GZip compression to reduce backup size
- **Retention policy**: Keeps all backups for 7 days, daily backups for 30 days, weekly for 8 weeks, etc.
- **Backup frequency**: Daily automatic backups at 1 AM (configured in the scheduler)

You can modify these settings in `config/backup.php` as needed.

### Using Backup Commands

#### Interactive Mode

To interactively manage backups, run:

```bash
php artisan backup:manage
```

This will prompt you with options to create, list, restore, or delete backups.

#### Direct Commands

You can also directly execute specific backup actions:

1. Create a new backup:
   ```bash
   php artisan backup:manage create
   ```

2. List existing backups:
   ```bash
   php artisan backup:manage list
   ```

3. Delete a backup:
   ```bash
   php artisan backup:manage delete
   ```
   
4. Create a database-only backup:
   ```bash
   php artisan backup:run --only-db
   ```

5. Create a files-only backup:
   ```bash
   php artisan backup:run --only-files
   ```

6. Clean old backups (according to retention policy):
   ```bash
   php artisan backup:clean
   ```

### Automated Backups

The system is configured to automatically run backups according to the following schedule:

- Daily full backup at 1:00 AM
- Weekly cleanup of old backups (following the retention policy)

These scheduled tasks are defined in the `App\Console\Kernel` class.

## Cache Management

The application includes advanced cache management functionality to help optimize performance.

### Configuration

Cache settings are stored in `config/cache.php`. The key settings include:

- **Default driver**: File-based caching is the default (`CACHE_DRIVER=file` in .env)
- **Prefix**: All cache keys are prefixed with `cleaner_cache_` to avoid collisions
- **TTL**: Default time-to-live for cache items is 1 hour (3600 seconds)
- **Cache warming**: You can define keys to pre-warm in the configuration

### Using Cache Commands

#### Interactive Mode

To interactively manage the cache, run:

```bash
php artisan cache:manage
```

This will prompt you with options to clear, view stats, optimize, or warm the cache.

#### Direct Commands

You can also directly execute specific cache actions:

1. Clear all cache:
   ```bash
   php artisan cache:manage clear
   ```

2. Clear cache for a specific tag:
   ```bash
   php artisan cache:manage clear --tag=products
   ```

3. View cache statistics:
   ```bash
   php artisan cache:manage stats
   ```

4. Optimize the cache (clear and rebuild):
   ```bash
   php artisan cache:manage optimize
   ```

5. Warm the cache with predefined keys:
   ```bash
   php artisan cache:manage warm
   ```

### Automated Cache Management

The system is configured to automatically optimize the cache weekly on Sundays at 3:00 AM.

## Environment Variables

The following environment variables can be set in your `.env` file to configure backup and cache behavior:

### Backup Settings
```
BACKUP_ARCHIVE_PASSWORD=null         # Set to encrypt backups
BACKUP_NOTIFICATION_EMAIL=admin@example.com
```

### Cache Settings
```
CACHE_DRIVER=file                   # file, redis, memcached, etc.
CACHE_TTL=3600                      # Default cache TTL in seconds
CACHE_GC_PROBABILITY=1              # Garbage collection probability
CACHE_GC_DIVISOR=100                # Garbage collection divisor
CACHE_SUPPORTS_TAGS=false           # Set to true if using Redis/Memcached
CACHE_PREFIX=cleaner_cache_         # Prefix for all cache keys
```

## Extending Functionality

### Adding Custom Cache Warming

To add custom cache warming logic, edit the `warmCache()` method in `app/Console/Commands/CacheManagerCommand.php` and implement your specific logic based on the application needs.

### Extending Backup Features

To add custom backup behavior, you can extend the `App\Console\Commands\BackupManager` class or modify the existing methods to suit your specific requirements.

## Troubleshooting

### Backup Issues

- Ensure the web server has write permissions to the storage directory
- For large databases, increase PHP memory limit and execution time
- Check logs in `storage/logs` for detailed error information

### Cache Issues

- If using Redis or Memcached, ensure the service is running
- For file-based caching, ensure proper permissions on the cache directory
- Use `php artisan cache:manage stats` to diagnose cache issues 
