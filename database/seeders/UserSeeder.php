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
        $users = array(
            [
            'name' => 'Iskandar Dedi',
            'email' => 'iskandardedi755@gmail.com'
            ],
            [
            'name' => 'Rendi Adit',
            'email' => 'rendiadit007@gmail.com'
            ]
        );

        foreach($users as $user){
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => 'adskartini2023'
            ]);
        }
    }
}
