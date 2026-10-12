<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public const ADMIN_EMAIL = 'amritumanggor097@gmail.com';

    public const ADMIN_PASSWORD = 'amyaya123';

    public function run(): void
    {
        User::query()
            ->where('is_admin', true)
            ->orWhere('email', self::ADMIN_EMAIL)
            ->delete();

        User::query()->create([
            'name' => 'Admin Gen-Z',
            'email' => self::ADMIN_EMAIL,
            'password' => self::ADMIN_PASSWORD,
            'is_admin' => true,
        ]);
    }
}
