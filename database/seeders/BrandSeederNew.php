<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeederNew extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'APP',
                'slug' => 'app',
                'description' => 'Asia Pulp & Paper - Merek kertas terkemuka',
                'is_active' => true,
            ],
            [
                'name' => 'Sinar Dunia',
                'slug' => 'sinar-dunia',
                'description' => 'Sinar Dunia - Kertas berkualitas untuk kebutuhan kantor',
                'is_active' => true,
            ],
            [
                'name' => 'PaperOne',
                'slug' => 'paperone',
                'description' => 'PaperOne - Premium office paper',
                'is_active' => true,
            ],
            [
                'name' => 'Double A',
                'slug' => 'double-a',
                'description' => 'Double A - The Original Paper',
                'is_active' => true,
            ],
            [
                'name' => 'Faber Castell',
                'slug' => 'faber-castell',
                'description' => 'Faber Castell - Alat tulis berkualitas tinggi',
                'is_active' => true,
            ],
            [
                'name' => 'Pilot',
                'slug' => 'pilot',
                'description' => 'Pilot - Innovative writing instruments',
                'is_active' => true,
            ],
            [
                'name' => 'Joyko',
                'slug' => 'joyko',
                'description' => 'Joyko - Alat tulis kantor terpercaya',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
