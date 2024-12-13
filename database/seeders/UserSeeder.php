<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'test',
            'username' => 'test',
            'password' => Hash::make('112233'),
            'token' => 'test'
        ]);
        User::create([
            'name' => 'test2',
            'username' => 'test2',
            'password' => Hash::make('112233'),
            'token' => 'test2'
        ]);
    }
}
