<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@jumuiyafoundation.org'],
            [
                'name'     => 'Jumuiya Admin',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info('Admin user created: admin@jumuiyafoundation.org / password');
        $this->command->warn('Remember to change the password after first login!');
    }
}
