<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fashion', 'description' => 'Outfit kekinian dan aksesoris.'],
            ['name' => 'Gadget', 'description' => 'Elektronik dan aksesoris gadget.'],
            ['name' => 'Skincare', 'description' => 'Perawatan kulit dan kecantikan.'],
            ['name' => 'Home & Living', 'description' => 'Peralatan rumah dan dekorasi.'],
            ['name' => 'Hobi', 'description' => 'Barang hobi dan koleksi.'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category + ['slug' => Str::slug($category['name'])],
            );
        }
    }
}
