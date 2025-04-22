<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'الإلكترونيات',
                'description' => 'الأجهزة الإلكترونية ومكوناتها'
            ],
            [
                'name' => 'الملابس',
                'description' => 'الملابس ومنتجات الأزياء'
            ],
            [
                'name' => 'المنزل والحديقة',
                'description' => 'منتجات تحسين المنزل والبستنة'
            ],
            [
                'name' => 'الرياضة ومعدات الخارجية',
                'description' => 'المعدات الرياضية وتجهيزات الهواء الطلق'
            ],
            [
                'name' => 'الجمال والصحة',
                'description' => 'منتجات الجمال والعناية الشخصية والصحية'
            ],
            [
                'name' => 'الألعاب والترفيه',
                'description' => 'منتجات الترفيه للأطفال والكبار'
            ],
            [
                'name' => 'السيارات',
                'description' => 'قطع غيار وإكسسوارات للمركبات'
            ],
            [
                'name' => 'الكتب والوسائط',
                'description' => 'الكتب والأفلام والموسيقى وغيرها من الوسائط'
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
