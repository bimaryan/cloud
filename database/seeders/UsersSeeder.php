<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Dearyz',
            'email' => 'bimaryan046@gmail.com',
            'password' => Hash::make('@Dearyz2329'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
