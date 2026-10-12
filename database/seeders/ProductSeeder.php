<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['Fashion', 'Oversize Hoodie Puffer', 189000, 4.8, 1200, true, true, false],
            ['Fashion', 'Celana Cargo Baggy', 159000, 4.7, 860, true, false, true],
            ['Gadget', 'TWS Bass Boost', 249000, 4.9, 3400, true, true, true],
            ['Gadget', 'Smartwatch AMOLED', 399000, 4.6, 540, false, true, false],
            ['Skincare', 'Serum Niacinamide', 89000, 4.8, 2100, true, false, true],
            ['Skincare', 'Sunscreen SPF 50', 75000, 4.9, 5200, true, true, true],
            ['Home & Living', 'Lampu Tidur RGB', 99000, 4.5, 320, false, false, true],
            ['Home & Living', 'Rak Minimalis 3 Tingkat', 175000, 4.4, 210, false, false, false],
            ['Hobi', 'Keyboard Mechanical 65%', 349000, 4.7, 410, true, false, false],
            ['Hobi', 'Kamera Mini Vlog', 459000, 4.6, 260, false, true, false],
        ];

        foreach ($products as [$categoryName, $name, $price, $rating, $sold, $trending, $viral, $recommended]) {
            Product::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => Category::query()->where('name', $categoryName)->value('id'),
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => "{$name} pilihan Gen-Z dengan kualitas terbaik dan harga bersaing. Cocok buat kamu yang mau tampil maksimal tanpa boros.",
                    'price' => $price,
                    'rating' => $rating,
                    'sold_count' => $sold,
                    'affiliate_url' => 'https://shopee.co.id/search?keyword='.urlencode($name),
                    'is_trending' => $trending,
                    'is_viral' => $viral,
                    'is_recommended' => $recommended,
                    'status' => Product::STATUS_ACTIVE,
                ],
            );
        }
    }
}
