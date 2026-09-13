<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = (string) env('ADMIN_PASSWORD', '');
        $placeholder = $password === '' || $password === 'change-me';

        if (app()->environment('production') && $placeholder) {
            throw new RuntimeException('Set ADMIN_PASSWORD in the production environment before seeding. Do not use the change-me placeholder.');
        }

        if ($password === '') {
            $password = 'change-me';
        }

        $user = User::query()->firstOrCreate(
            ['email' => 'admin@islamtextile.com'],
            [
                'name' => 'Admin',
                'password' => $password,
            ]
        );

        if (! $user->hasRole('super-admin')) {
            $user->assignRole('super-admin');
        }
    }
}
