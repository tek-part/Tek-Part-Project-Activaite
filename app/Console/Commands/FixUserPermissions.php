<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUserPermissions extends Command
{
    protected $signature = 'permissions:fix';
    protected $description = 'Fix user permissions by syncing with their roles';

    public function handle()
    {
        $this->info('Starting permission fix...');

        // Get all users
        $users = User::with('roles')->get();
        $this->info("Found {$users->count()} users to process");

        // Get license permissions
        $licensePermissions = Permission::where('name', 'like', '%license%')
            ->orWhere('name', 'like', '%product%')
            ->get();

        $this->info("Found {$licensePermissions->count()} license-related permissions");

        // For each user, check their roles and assign permissions directly
        foreach ($users as $user) {
            $this->info("Processing user: {$user->name}");

            // Clear existing user-permission direct relationships
            DB::table('permission_user')
                ->where('user_id', $user->id)
                ->where('user_type', get_class($user))
                ->delete();

            // Get user roles
            $userRoles = $user->roles;

            if ($userRoles->isEmpty()) {
                $this->warn("  - User has no roles, skipping");
                continue;
            }

            $this->line("  - User has roles: " . $userRoles->pluck('name')->join(', '));

            // Get all permissions for the user's roles
            $permissionIds = DB::table('permission_role')
                ->whereIn('role_id', $userRoles->pluck('id'))
                ->pluck('permission_id')
                ->unique();

            $this->line("  - Assigning {$permissionIds->count()} permissions");

            // Assign permissions directly to user
            foreach ($permissionIds as $permissionId) {
                DB::table('permission_user')->insert([
                    'permission_id' => $permissionId,
                    'user_id' => $user->id,
                    'user_type' => get_class($user),
                ]);
            }
        }

        $this->info('Permission fix completed successfully!');
        $this->info('You may need to clear the application cache with: php artisan optimize:clear');

        return 0;
    }
}