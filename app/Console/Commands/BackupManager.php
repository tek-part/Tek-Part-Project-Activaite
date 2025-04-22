<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:manage
                            {action? : The action to perform (create/list/restore/delete)}
                            {filename? : The backup filename when restoring or deleting}
                            {--force : Force the operation without asking for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage system backups (create, list, restore, delete)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action') ?? $this->choice(
            'What action would you like to perform?',
            ['create', 'list', 'restore', 'delete'],
            0
        );

        switch ($action) {
            case 'create':
                $this->createBackup();
                break;
            case 'list':
                $this->listBackups();
                break;
            case 'restore':
                $this->restoreBackup();
                break;
            case 'delete':
                $this->deleteBackup();
                break;
            default:
                $this->error('Invalid action specified.');
                return 1;
        }

        return 0;
    }

    /**
     * Create a new backup.
     */
    protected function createBackup()
    {
        $this->info('Creating a new backup...');
        $this->info('This might take some time depending on the size of your application.');

        $this->newLine();
        $bar = $this->output->createProgressBar(3);
        $bar->start();

        $this->info('Backing up database...');
        $bar->advance();

        $this->info('Backing up files...');
        Artisan::call('backup:run', ['--only-files' => true]);
        $bar->advance();

        $this->info('Finalizing backup...');
        Artisan::call('backup:run');
        $bar->advance();

        $bar->finish();
        $this->newLine(2);

        $this->info('Backup created successfully!');
    }

    /**
     * List all available backups.
     */
    protected function listBackups()
    {
        $files = Storage::disk('local')->files('Laravel');

        if (empty($files)) {
            $this->info('No backups found.');
            return;
        }

        $backups = [];
        foreach ($files as $file) {
            if (strpos($file, '.zip') !== false) {
                $size = Storage::disk('local')->size($file);
                $date = Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file));

                $backups[] = [
                    'filename' => basename($file),
                    'size' => $this->formatSize($size),
                    'date' => $date->format('Y-m-d H:i:s'),
                    'age' => $date->diffForHumans(),
                ];
            }
        }

        $this->table(
            ['Filename', 'Size', 'Date', 'Age'],
            $backups
        );
    }

    /**
     * Restore a backup.
     */
    protected function restoreBackup()
    {
        $filename = $this->argument('filename');

        if (!$filename) {
            $files = Storage::disk('local')->files('Laravel');
            $backups = [];

            foreach ($files as $file) {
                if (strpos($file, '.zip') !== false) {
                    $backups[] = basename($file);
                }
            }

            if (empty($backups)) {
                $this->error('No backups found to restore.');
                return;
            }

            $filename = $this->choice('Which backup would you like to restore?', $backups);
        }

        $fullPath = 'Laravel/' . $filename;

        if (!Storage::disk('local')->exists($fullPath)) {
            $this->error("Backup file '{$filename}' not found.");
            return;
        }

        if (!$this->option('force') && !$this->confirm('Are you sure you want to restore this backup? This will replace your current data.', false)) {
            $this->info('Restore operation cancelled.');
            return;
        }

        $this->info('Restoring backup...');
        $this->warn('This feature requires additional implementation for your specific application needs.');
        $this->info('For security reasons, you should implement custom restore logic based on your application architecture.');
        $this->info('Typically, this would involve:');
        $this->info('1. Extracting the backup zip file');
        $this->info('2. Importing the database');
        $this->info('3. Restoring files to their correct locations');

        // Example implementation outline (not operational):
        // $backupPath = storage_path('app/' . $fullPath);
        // $extractPath = storage_path('app/backup-restore-temp');
        //
        // // Extract the backup
        // $zip = new \ZipArchive();
        // $zip->open($backupPath);
        // $zip->extractTo($extractPath);
        // $zip->close();
        //
        // // Restore database
        // $dbDumpPath = $extractPath . '/db-dumps/mysql-my_database.sql';
        // // Import database using appropriate method for your database
        //
        // // Restore files
        // // Move extracted files to their correct locations
        //
        // // Clean up
        // File::deleteDirectory($extractPath);

        $this->info('Please implement the actual restore logic for your application.');
    }

    /**
     * Delete a backup.
     */
    protected function deleteBackup()
    {
        $filename = $this->argument('filename');

        if (!$filename) {
            $files = Storage::disk('local')->files('Laravel');
            $backups = [];

            foreach ($files as $file) {
                if (strpos($file, '.zip') !== false) {
                    $backups[] = basename($file);
                }
            }

            if (empty($backups)) {
                $this->error('No backups found to delete.');
                return;
            }

            $filename = $this->choice('Which backup would you like to delete?', $backups);
        }

        $fullPath = 'Laravel/' . $filename;

        if (!Storage::disk('local')->exists($fullPath)) {
            $this->error("Backup file '{$filename}' not found.");
            return;
        }

        if (!$this->option('force') && !$this->confirm('Are you sure you want to delete this backup?', false)) {
            $this->info('Delete operation cancelled.');
            return;
        }

        Storage::disk('local')->delete($fullPath);
        $this->info("Backup '{$filename}' deleted successfully.");
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
}
