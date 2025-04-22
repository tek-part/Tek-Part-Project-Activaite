<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings', ['only' => ['index', 'create', 'delete']]);
    }

    /**
     * Display a listing of backups
     */
    public function index()
    {
        $backups = [];

        // Check direct backup folder first (most recent backups)
        $directBackupPath = storage_path('app/' . config('backup.backup.name'));
        if (is_dir($directBackupPath)) {
            $directFiles = glob($directBackupPath . '/*.zip');
            foreach ($directFiles as $file) {
                $fileName = basename($file);
                $filePath = config('backup.backup.name') . '/' . $fileName;

                $date = Carbon::createFromTimestamp(filemtime($file));
                $backups[] = [
                    'filename' => $fileName,
                    'file_path' => $filePath,
                    'size' => $this->bytesToHuman(filesize($file)),
                    'date' => $date->format('d-m-Y H:i:s'),
                    'age' => $date->diffForHumans(),
                ];
            }
        }

        // Then check in storage
        try {
            $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
            $files = $disk->files(config('backup.backup.name'));

            // Make an array of backup files, with their filesize and creation date
            foreach ($files as $file) {
                // Only take the zip files
                if (substr($file, -4) == '.zip' && $disk->exists($file)) {
                    $fileName = str_replace(config('backup.backup.name') . '/', '', $file);

                    // Check if file is already in the list
                    $exists = false;
                    foreach ($backups as $backup) {
                        if ($backup['filename'] === $fileName) {
                            $exists = true;
                            break;
                        }
                    }

                    if (!$exists) {
                        $date = Carbon::createFromTimestamp($disk->lastModified($file));

                        $backups[] = [
                            'filename' => $fileName,
                            'file_path' => $file,
                            'size' => $this->bytesToHuman($disk->size($file)),
                            'date' => $date->format('d-m-Y H:i:s'),
                            'age' => $date->diffForHumans(),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Error reading backups: " . $e->getMessage());
        }

        // Check for any .sql.zip files (older format)
        if (is_dir($directBackupPath)) {
            $directSqlFiles = glob($directBackupPath . '/*.sql.zip');
            foreach ($directSqlFiles as $file) {
                $fileName = basename($file);
                $filePath = config('backup.backup.name') . '/' . $fileName;

                // Check if file is already in the list
                $exists = false;
                foreach ($backups as $backup) {
                    if ($backup['filename'] === $fileName) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $date = Carbon::createFromTimestamp(filemtime($file));
                    $backups[] = [
                        'filename' => $fileName,
                        'file_path' => $filePath,
                        'size' => $this->bytesToHuman(filesize($file)),
                        'date' => $date->format('d-m-Y H:i:s'),
                        'age' => $date->diffForHumans(),
                    ];
                }
            }
        }

        // Sort by date
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return view('admin.settings.backup', compact('backups'));
    }

    /**
     * Create a new backup
     */
    public function create()
    {
        try {
            // Increase execution time limit
            ini_set('max_execution_time', 300); // 5 minutes
            set_time_limit(300);

            // Set memory limit
            ini_set('memory_limit', '512M');

            // Use direct DB backup instead of the Laravel backup package
            $result = $this->createDatabaseBackup();

            if ($result['success']) {
                // Add detailed diagnostics to the success message
                $successMessage = __('translations.backup-created-success') . ' ' .
                                 __('translations.in') . ' ' . $result['time'] . ' ' .
                                 __('translations.seconds') . '. ' .
                                 __('translations.size') . ': ' . $result['size'];

                return redirect()->route('admin.settings.backup')
                    ->with('success', $successMessage)
                    ->with('diagnostics', $result);
            } else {
                // If direct backup fails, try using the Laravel backup package as fallback
                Artisan::call('backup:run --only-db --disable-notifications');
                Log::info("Database backup completed using Laravel backup package");
                return redirect()->route('admin.settings.backup')->with('success', __('translations.backup-created-success'));
            }
        } catch (\Exception $e) {
            Log::error("Database backup failed: " . $e->getMessage());
            return redirect()->route('admin.settings.backup')->with('error', $e->getMessage());
        }
    }

    /**
     * Create database backup using mysqldump directly
     */
    private function createDatabaseBackup()
    {
        try {
            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            // Start timing the backup process
            $startTime = microtime(true);

            // Set backup file path
            $backupFolder = storage_path('app/' . config('backup.backup.name'));

            // Create directory if it doesn't exist
            if (!file_exists($backupFolder)) {
                mkdir($backupFolder, 0755, true);
            }

            // Create backup filename with date
            $filename = $database . '_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupFolder . '/' . $filename;

            // Try alternative methods for mysqldump
            $success = false;
            $errorMessages = [];

            // Method 1: Using exec with optimized parameters
            try {
                // Build the mysqldump command with optimization flags
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s --single-transaction --quick --lock-tables=false --set-gtid-purged=OFF --skip-add-locks --skip-comments %s > %s',
                    escapeshellarg($username),
                    escapeshellarg($password),
                    escapeshellarg($host),
                    escapeshellarg($database),
                    escapeshellarg($filePath)
                );

                Log::info("Trying mysqldump method 1");

                exec($command, $output, $returnVar);

                if ($returnVar === 0 && file_exists($filePath) && filesize($filePath) > 0) {
                    $success = true;
                    Log::info("Method 1 succeeded");
                } else {
                    $errorMessages[] = "Method 1 failed with code: $returnVar";
                }
            } catch (\Exception $e) {
                $errorMessages[] = "Method 1 exception: " . $e->getMessage();
            }

            // Method 2: Using shell_exec with a simpler command if Method 1 failed
            if (!$success) {
                try {
                    $command = sprintf(
                        'mysqldump -u %s -p%s -h %s %s > %s',
                        $username,
                        $password,
                        $host,
                        $database,
                        $filePath
                    );

                    Log::info("Trying mysqldump method 2");

                    $shellOutput = shell_exec($command);

                    if (file_exists($filePath) && filesize($filePath) > 0) {
                        $success = true;
                        Log::info("Method 2 succeeded");
                    } else {
                        $errorMessages[] = "Method 2 failed: No output file generated";
                    }
                } catch (\Exception $e) {
                    $errorMessages[] = "Method 2 exception: " . $e->getMessage();
                }
            }

            // Method 3: Using the mysql PHP extension directly
            if (!$success && extension_loaded('mysqli')) {
                try {
                    Log::info("Trying mysqldump method 3 (PHP mysqli)");

                    $mysqli = new \mysqli($host, $username, $password, $database);

                    if ($mysqli->connect_error) {
                        $errorMessages[] = "Method 3 connection failed: " . $mysqli->connect_error;
                    } else {
                        // Get all tables
                        $tables = [];
                        $result = $mysqli->query('SHOW TABLES');
                        while ($row = $result->fetch_array()) {
                            $tables[] = $row[0];
                        }

                        $sqlScript = "-- Database backup generated by the application\n";
                        $sqlScript .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";

                        // Process each table
                        foreach ($tables as $table) {
                            // Get table structure
                            $result = $mysqli->query("SHOW CREATE TABLE $table");
                            $row = $result->fetch_array();
                            $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
                            $sqlScript .= $row[1] . ";\n\n";

                            // Get table data
                            $result = $mysqli->query("SELECT * FROM $table");
                            $columnCount = $result->field_count;

                            // Output table data
                            while ($row = $result->fetch_array()) {
                                $sqlScript .= "INSERT INTO `$table` VALUES(";
                                for ($i = 0; $i < $columnCount; $i++) {
                                    if (isset($row[$i])) {
                                        $sqlScript .= "'" . $mysqli->real_escape_string($row[$i]) . "'";
                                    } else {
                                        $sqlScript .= "NULL";
                                    }

                                    if ($i < ($columnCount - 1)) {
                                        $sqlScript .= ',';
                                    }
                                }
                                $sqlScript .= ");\n";
                            }

                            $sqlScript .= "\n\n";
                        }

                        // Save the SQL script to file
                        if (file_put_contents($filePath, $sqlScript)) {
                            $success = true;
                            Log::info("Method 3 succeeded");
                        } else {
                            $errorMessages[] = "Method 3 failed to save file";
                        }

                        $mysqli->close();
                    }
                } catch (\Exception $e) {
                    $errorMessages[] = "Method 3 exception: " . $e->getMessage();
                }
            }

            // If all methods failed
            if (!$success) {
                Log::error("All backup methods failed: " . implode("; ", $errorMessages));
                return ['success' => false, 'message' => 'All backup methods failed: ' . implode("; ", $errorMessages)];
            }

            // Calculate elapsed time for dump
            $elapsedTime = microtime(true) - $startTime;
            Log::info("Database dump execution time: " . round($elapsedTime, 2) . " seconds");

            // Check file size
            if (file_exists($filePath)) {
                $fileSize = filesize($filePath);
                $humanFileSize = $this->bytesToHuman($fileSize);
                Log::info("SQL dump file size: " . $humanFileSize);
            }

            // Start timing the zip process
            $zipStartTime = microtime(true);

            // Create zip file
            $zip = new \ZipArchive();
            $zipName = str_replace('.sql', '.zip', $filePath);

            if ($zip->open($zipName, \ZipArchive::CREATE) === TRUE) {
                $zip->addFile($filePath, basename($filePath));
                $zip->close();

                // Calculate zip elapsed time
                $zipElapsedTime = microtime(true) - $zipStartTime;
                Log::info("ZIP creation time: " . round($zipElapsedTime, 2) . " seconds");

                // Remove the SQL file after creating ZIP
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Check zip file size
                if (file_exists($zipName)) {
                    $zipFileSize = filesize($zipName);
                    $humanZipFileSize = $this->bytesToHuman($zipFileSize);
                    Log::info("ZIP file size: " . $humanZipFileSize);
                }

                // Calculate total elapsed time
                $totalElapsedTime = microtime(true) - $startTime;
                Log::info("Total backup time: " . round($totalElapsedTime, 2) . " seconds");

                return [
                    'success' => true,
                    'message' => 'Backup created successfully',
                    'time' => round($totalElapsedTime, 2),
                    'size' => $humanZipFileSize ?? 'Unknown'
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to create ZIP file'];
            }
        } catch (\Exception $e) {
            Log::error("Direct database backup failed: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Delete a backup file
     */
    public function delete($filename)
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $file = config('backup.backup.name') . '/' . $filename;

        if ($disk->exists($file)) {
            $disk->delete($file);
            return redirect()->route('admin.settings.backup')->with('success', __('translations.backup-deleted-success'));
        }

        return redirect()->route('admin.settings.backup')->with('error', __('translations.backup-deleted-error'));
    }

    /**
     * Convert bytes to human readable format
     */
    private function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Download a backup file
     */
    public function download($filename)
    {
        // Check direct path first
        $directPath = storage_path('app/' . config('backup.backup.name') . '/' . $filename);
        if (file_exists($directPath)) {
            return response()->download($directPath);
        }

        // Then check in storage
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $file = config('backup.backup.name') . '/' . $filename;

        if ($disk->exists($file)) {
            return response()->download(storage_path('app/' . $file));
        }

        return redirect()->route('admin.settings.backup')->with('error', __('translations.backup-not-found'));
    }
}
