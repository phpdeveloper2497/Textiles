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
            'phone' => '+998945400807',
            'is_admin' => true,
            'password' => 'password'

        ]);
        $user->assignRole('admin');

        $user = User::create([
            'first_name' => 'warehouse',
            'last_name' => 'manager',
            'nickname' => 'john',
            'phone' => '+99995719769',
            'is_admin' => false,
            'password' => 'password'
        ]);
        $user->assignRole('Ombor mudiri');
//
//        $user = User::create([
//            'first_name' => 'case',
//            'last_name' => 'manager',
//            'nickname' => 'doe',
//            'phone' => '',
//            'password' => 'password'
//        ]);
//        $user->assignRole('Ish boshqaruvchi');
    }
}
