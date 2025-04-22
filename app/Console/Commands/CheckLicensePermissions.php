<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckLicensePermissions extends Command
{
    protected $signature = 'check:license-permissions {email? : The email of the user to check}';
    protected $description = 'Check license permissions for a specific user or all users';

    public function handle()
    {
        // First check database entries
        $this->checkDatabasePermissions();

        $email = $this->argument('email');

        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("User with email {$email} not found.");
                return 1;
            }
            $this->checkPermissionsForUser($user);
        } else {
            $users = User::all();
            foreach ($users as $user) {
                $this->checkPermissionsForUser($user);
                $this->line('------------------------');
            }
        }

        return 0;
    }

    private function checkDatabasePermissions()
    {
        $this->info("Checking Database Permissions Table:");

        // Count total permissions
        $totalPermissions = Permission::count();
        $this->line("Total permissions in database: {$totalPermissions}");

        // Count license-related permissions
        $licensePermissions = Permission::where('name', 'like', '%license%')
            ->orWhere('name', 'like', '%product%')
            ->count();
        $this->line("License-related permissions: {$licensePermissions}");

        // Check permission_role entries
        $this->info("Permission-Role assignments:");

        $roles = Role::all();
        foreach ($roles as $role) {
            $assignedCount = DB::table('permission_role')
                ->where('role_id', $role->id)
                ->count();

            $licenseCount = DB::table('permission_role')
                ->join('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                ->where('role_id', $role->id)
                ->where(function($q) {
                    $q->where('permissions.name', 'like', '%license%')
                      ->orWhere('permissions.name', 'like', '%product%');
                })
                ->count();

            $this->line("Role: {$role->name} - Total permissions: {$assignedCount}, License permissions: {$licenseCount}");
        }

        $this->line("------------------------");
    }

    private function checkPermissionsForUser(User $user)
    {
        $this->info("User: {$user->name} ({$user->email})");

        // Get the user's roles
        $roles = $user->roles()->get();
        $this->line("Roles: " . $roles->pluck('name')->join(', '));

        // Get license-related permissions
        $licensePermissions = Permission::where('name', 'like', '%license%')
            ->orWhere('name', 'like', '%product%')
            ->get();

        $this->line("License Permissions:");
        foreach ($licensePermissions as $permission) {
            $hasPermission = $user->can($permission->name);
            $this->line("- {$permission->name}: " . ($hasPermission ? '✓' : '✗'));
        }
    }
}
