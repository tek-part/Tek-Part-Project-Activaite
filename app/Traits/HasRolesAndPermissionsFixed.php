<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

trait HasRolesAndPermissionsFixed
{
    /**
     * Check if the user has a specific permission directly or through a role
     *
     * @param string|array $abilities
     * @param array|mixed $arguments
     * @return bool
     */
    public function can($abilities, $arguments = [])
    {
        // If this is a permission check coming from our middleware
        if (is_string($abilities) && empty($arguments)) {
            return $this->hasDirectPermission($abilities) || $this->hasPermissionThroughRole($abilities);
        }

        // Otherwise, defer to Laravel's default implementation
        return parent::can($abilities, $arguments);
    }

    /**
     * Check if the user has a specific permission directly
     *
     * @param string $permission
     * @return bool
     */
    public function hasDirectPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();

            if (!$permission) {
                return false;
            }
        }

        // Check direct permission assignment in the database
        return DB::table('permission_user')
            ->where('permission_id', $permission->id)
            ->where('user_id', $this->id)
            ->where('user_type', get_class($this))
            ->exists();
    }

    /**
     * Check if the user has permission through their roles
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermissionThroughRole($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();

            if (!$permission) {
                return false;
            }
        }

        // Get user's role IDs
        $roleIds = DB::table('role_user')
            ->where('user_id', $this->id)
            ->where('user_type', get_class($this))
            ->pluck('role_id');

        // Check if any of the user's roles have the permission
        return DB::table('permission_role')
            ->where('permission_id', $permission->id)
            ->whereIn('role_id', $roleIds)
            ->exists();
    }

    /**
     * Check if user has any of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if ($this->hasDirectPermission($permission) || $this->hasPermissionThroughRole($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions($permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            if (!($this->hasDirectPermission($permission) || $this->hasPermissionThroughRole($permission))) {
                return false;
            }
        }

        return true;
    }
}
