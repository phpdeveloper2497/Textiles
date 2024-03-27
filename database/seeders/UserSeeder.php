<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'nickname' => 'admin',
            'phone' => '+998900828859',
            'password' => 'password'

        ]);
        $user->assignRole('admin');

        $user = User::create([
            'first_name' => 'warehouse',
            'last_name' => 'manager',
            'nickname' => 'john',
            'phone' => '+998900818859',
            'password' => 'password'
        ]);
        $user->assignRole('warehouse_manager');

        $user = User::create([
            'first_name' => 'case',
            'last_name' => 'manager',
            'nickname' => 'doe',
            'phone' => '+998900848859',
            'password' => 'password'
        ]);
        $user->assignRole('case_manager');
    }
}
