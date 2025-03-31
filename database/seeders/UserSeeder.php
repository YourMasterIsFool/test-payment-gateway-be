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
        //

    
        $users = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin'),
            ],
            [
                'name' => 'user1',
                'email' => 'user1@gmail.com',
                'password' => bcrypt('user1'),
            ]
        ];

        foreach ($users as $user) {
            $findUser =  User::where("email", $user['email'])->first();
            if(!$findUser) {
                User::create($user);
            }
        }

    }
}
