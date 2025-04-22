<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {

        // users Dashboard
        Permission::create([
            'name'         => 'dashboard-list',
            'display_name' => 'عرض لوحة التحكم',
        ]);




        // users permissions
        Permission::create([
            'name'         => 'users-list',
            'display_name' => 'عرض المستخدمين',
        ]);
        Permission::create([
            'name'         => 'users-create',
            'display_name' => 'اضافة المستخدمين',
        ]);
        Permission::create([
            'name'         => 'users-edit',
            'display_name' => 'تعديل المستخدمين',
        ]);
        Permission::create([
            'name'         => 'users-delete',
            'display_name' => 'حذف المستخدمين',
        ]);



        // roles permissions
        Permission::create([
            'name'         => 'roles-list',
            'display_name' => 'عرض مستوى الصلاحيات',
        ]);
        Permission::create([
            'name'         => 'roles-create',
            'display_name' => 'اضافة مستوى الصلاحيات',
        ]);
        Permission::create([
            'name'         => 'roles-edit',
            'display_name' => 'تعديل مستوى الصلاحيات',
        ]);
        Permission::create([
            'name'         => 'roles-delete',
            'display_name' => 'حذف مستوى الصلاحيات',
        ]);





        // settings permissions
        Permission::create([
            'name'         => 'settings',
            'display_name' => 'الاعدادات',
        ]);


        // Products permissions
        Permission::create([
            'name'         => 'products-list',
            'display_name' => 'عرض المنتجات',
        ]);
        Permission::create([
            'name'         => 'products-create',
            'display_name' => 'اضافة منتج',
        ]);
        Permission::create([
            'name'         => 'products-edit',
            'display_name' => 'تعديل منتج',
        ]);
        Permission::create([
            'name'         => 'products-delete',
            'display_name' => 'حذف منتج',
        ]);
        Permission::create([
            'name'         => 'products-toggle-status',
            'display_name' => 'تغيير حالة المنتج',
        ]);

        // Categories permissions
        Permission::create([
            'name'         => 'categories-list',
            'display_name' => 'عرض الأقسام',
        ]);
        Permission::create([
            'name'         => 'categories-create',
            'display_name' => 'اضافة قسم',
        ]);
        Permission::create([
            'name'         => 'categories-edit',
            'display_name' => 'تعديل قسم',
        ]);
        Permission::create([
            'name'         => 'categories-delete',
            'display_name' => 'حذف قسم',
        ]);

        // Licenses permissions
        Permission::create([
            'name'         => 'licenses-list',
            'display_name' => 'عرض التراخيص',
        ]);
        Permission::create([
            'name'         => 'licenses-create',
            'display_name' => 'اضافة ترخيص',
        ]);
        Permission::create([
            'name'         => 'licenses-edit',
            'display_name' => 'تعديل ترخيص',
        ]);
        Permission::create([
            'name'         => 'licenses-delete',
            'display_name' => 'حذف ترخيص',
        ]);
        Permission::create([
            'name'         => 'licenses-activate',
            'display_name' => 'تفعيل ترخيص',
        ]);
        Permission::create([
            'name'         => 'licenses-deactivate',
            'display_name' => 'إلغاء تفعيل ترخيص',
        ]);
        Permission::create([
            'name'         => 'licenses-regenerate-code',
            'display_name' => 'إعادة انشاء رمز التفعيل',
        ]);
        Permission::create([
            'name'         => 'licenses-revoke',
            'display_name' => 'سحب ترخيص',
        ]);
        Permission::create([
            'name'         => 'licenses-logs',
            'display_name' => 'عرض سجلات التراخيص',
        ]);

        // License API permissions
        Permission::create([
            'name'         => 'license-api-access',
            'display_name' => 'استخدام واجهة برمجة التراخيص',
        ]);
        Permission::create([
            'name'         => 'license-docs-access',
            'display_name' => 'الوصول إلى وثائق التكامل',
        ]);
    }
}
