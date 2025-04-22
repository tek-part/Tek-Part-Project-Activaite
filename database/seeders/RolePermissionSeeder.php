<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing role-permission associations
        DB::table('permission_role')->truncate();

        // Get all roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $hrRole = Role::where('name', 'hr')->first();
        $accountantRole = Role::where('name', 'accountant')->first();

        // Get all permissions and license permissions separately
        $allPermissions = Permission::all()->pluck('id')->toArray();

        // License permissions - Define as a separate array for reuse
        $licensePermissionNames = [
            'products-list',
            'products-create',
            'products-edit',
            'products-delete',
            'products-toggle-status',
            'categories-list',
            'categories-create',
            'categories-edit',
            'categories-delete',
            'licenses-list',
            'licenses-create',
            'licenses-edit',
            'licenses-delete',
            'licenses-activate',
            'licenses-deactivate',
            'licenses-regenerate-code',
            'licenses-revoke',
            'licenses-logs',
            'license-api-access',
            'license-docs-access',
        ];

        $licensePermissions = Permission::whereIn('name', $licensePermissionNames)->pluck('id')->toArray();

        // Directly assign all permissions to admin role
        foreach ($allPermissions as $permId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permId,
                'role_id' => $adminRole->id
            ]);
        }

        // Manager role permissions
        $managerPermissionNames = array_merge([
            'dashboard-list',
            'cleaners-list', 'cleaners-create', 'cleaners-edit', 'cleaners-delete',
            'customers-list', 'customers-create', 'customers-edit', 'customers-delete',
            'packages-list', 'packages-create', 'packages-edit', 'packages-delete',
            'subscriptions-list', 'subscriptions-create', 'subscriptions-edit', 'subscriptions-delete',
            'bookings-list', 'bookings-create', 'bookings-edit', 'bookings-delete',
            'invoices-list', 'invoices-create', 'invoices-edit', 'invoices-show', 'invoices-delete',
            'reports-list', 'reports-create', 'reports-edit', 'reports-delete', 'reports-revenue',
            'visits-list',
            'settings',
            // Some employee module permissions for manager
            'departments-list', 'departments-create', 'departments-edit',
            'employees-list', 'employees-view',
            'attendances-list', 'attendances-bulk',
            'employee-reports-view',
        ], $licensePermissionNames);

        $managerPermissions = Permission::whereIn('name', $managerPermissionNames)->pluck('id')->toArray();

        foreach ($managerPermissions as $permId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permId,
                'role_id' => $managerRole->id
            ]);
        }

        // HR role permissions - Focus on employee management
        $hrPermissionNames = [
            'dashboard-list',
            'employees-list', 'employees-create', 'employees-edit', 'employees-delete', 'employees-view',
            'departments-list', 'departments-create', 'departments-edit', 'departments-delete',
            'salaries-list', 'salaries-create', 'salaries-edit', 'salaries-delete', 'salaries-view', 'salaries-payslip',
            'allowances-manage',
            'deductions-manage',
            'attendances-list', 'attendances-create', 'attendances-edit', 'attendances-delete', 'attendances-bulk',
            'employee-reports-view', 'salary-reports-view', 'attendance-reports-view', 'monthly-salary-reports-view'
        ];

        $hrPermissions = Permission::whereIn('name', $hrPermissionNames)->pluck('id')->toArray();

        foreach ($hrPermissions as $permId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permId,
                'role_id' => $hrRole->id
            ]);
        }

        // Accountant role permissions - Focus on financial aspects plus license viewing
        $accountantPermissionNames = [
            'dashboard-list',
            'invoices-list', 'invoices-create', 'invoices-edit', 'invoices-show',
            'expenses-list', 'expenses-create', 'expenses-edit',
            'reports-list', 'reports-revenue',
            'salaries-list', 'salaries-view', 'salaries-payslip',
            'salary-reports-view', 'monthly-salary-reports-view',
            // Add basic license viewing permissions for accountants
            'products-list',
            'licenses-list',
            'licenses-logs'
        ];

        $accountantPermissions = Permission::whereIn('name', $accountantPermissionNames)->pluck('id')->toArray();

        foreach ($accountantPermissions as $permId) {
            DB::table('permission_role')->insert([
                'permission_id' => $permId,
                'role_id' => $accountantRole->id
            ]);
        }
    }
}
