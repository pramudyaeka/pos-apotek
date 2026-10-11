<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'owner'],
            [
                'name' => 'Apotek Owner',
                'password' => Hash::make('password'),
                'role' => 'Owner',
                'status' => 'Active',
            ]
        );

        $this->command?->info('Owner: owner / password');
    }
}
