<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckPermissionsDirect extends Command
{
    protected $signature = 'check:permissions-direct';
    protected $description = 'Check permissions directly from database tables';

    public function handle()
    {
        $this->info("Checking Direct Database Permissions:");

        // Get license permissions
        $licensePermissions = Permission::where('name', 'like', '%license%')
            ->orWhere('name', 'like', '%product%')
            ->get();

        $this->info("Found {$licensePermissions->count()} license-related permissions");

        // Get all users
        $users = User::all();
        foreach ($users as $user) {
            $this->info("User: {$user->name} ({$user->email})");

            // Get user's roles
            $roleIds = DB::table('role_user')
                ->where('user_id', $user->id)
                ->where('user_type', get_class($user))
                ->pluck('role_id');

            $roles = Role::whereIn('id', $roleIds)->get();
            $this->line("Roles: " . $roles->pluck('name')->join(', '));

            // Check direct permissions
            $this->line("Direct License Permissions:");
            foreach ($licensePermissions as $permission) {
                $hasDirectPermission = DB::table('permission_user')
                    ->where('permission_id', $permission->id)
                    ->where('user_id', $user->id)
                    ->where('user_type', get_class($user))
                    ->exists();

                $this->line("- {$permission->name}: " . ($hasDirectPermission ? '✓' : '✗'));
            }

            // Check role permissions
            $this->line("Role-based License Permissions:");
            foreach ($licensePermissions as $permission) {
                $hasRolePermission = DB::table('permission_role')
                    ->where('permission_id', $permission->id)
                    ->whereIn('role_id', $roleIds)
                    ->exists();

                $this->line("- {$permission->name}: " . ($hasRolePermission ? '✓' : '✗'));
            }

            $this->line('------------------------');
        }

        return 0;
    }
}