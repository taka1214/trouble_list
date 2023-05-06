<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
            'name' => 'usertest',
            'email' => 'usertest@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 11:15:20',
            ],
            [
            'name' => 'usertest2',
            'email' => 'usertest2@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 12:15:20',
            ],
            [
            'name' => 'usertest3',
            'email' => 'usertest3@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 13:15:20',
            ],
            [
            'name' => 'usertest4',
            'email' => 'usertest4@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 14:15:20',
            ],
            [
            'name' => 'usertest5',
            'email' => 'usertest5@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 15:15:20',
            ],
            [
            'name' => 'usertest6',
            'email' => 'usertest6@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 16:15:20',
            ],
            [
            'name' => 'usertest7',
            'email' => 'usertest7@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 17:15:20',
            ],
            [
            'name' => 'usertest8',
            'email' => 'usertest8@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 18:15:20',
            ],
            [
            'name' => 'usertest9',
            'email' => 'usertest9@gmail.com',
            'password' => Hash::make('password123'),
            'created_at' => '2023/3/23 19:15:20',
            ],
        ]);
    }
}
