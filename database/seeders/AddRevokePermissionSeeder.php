<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddRevokePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Check if permission already exists
        $permissionExists = Permission::where('name', 'licenses-revoke')->exists();

        if (!$permissionExists) {
            // Create the permission
            $permission = Permission::create([
                'name'         => 'licenses-revoke',
                'display_name' => 'سحب ترخيص',
            ]);

            // Get admin role and assign permission
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $adminRole->id
                ]);
            }

            // Get manager role and assign permission
            $managerRole = Role::where('name', 'manager')->first();
            if ($managerRole) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $managerRole->id
                ]);
            }

            $this->command->info('License revoke permission added successfully.');
        } else {
            $this->command->info('License revoke permission already exists.');
        }
    }
}
