<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Gen-Z Finds',
            'tagline' => 'Belanja cerdas, produk paling worth it buat kamu',
            'description' => 'Kurasi produk viral, trending, dan rekomendasi terbaik dengan link affiliate terpercaya.',
            'contact_email' => 'halo@gen-z.test',
            'instagram_url' => 'https://instagram.com',
            'tiktok_url' => 'https://tiktok.com',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
