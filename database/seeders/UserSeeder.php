<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Owner BizTrack',
            'email'    => 'owner@biztrack.com',
            'password' => Hash::make('password'),
            'role'     => 'owner',
        ]);

        User::create([
            'name'     => 'Kasir BizTrack',
            'email'    => 'cashier@biztrack.com',
            'password' => Hash::make('password'),
            'role'     => 'cashier',
        ]);
    }
}
