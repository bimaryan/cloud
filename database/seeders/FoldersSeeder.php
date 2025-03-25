<?php

namespace Database\Seeders;

use App\Models\Folder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FoldersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $folders = [
            ['uuid' => Str::uuid(), 'name' => 'Downloads', 'user_id' => 1, 'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['uuid' => Str::uuid(), 'name' => 'Documents', 'user_id' => 1, 'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['uuid' => Str::uuid(), 'name' => 'Image', 'user_id' => 1, 'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['uuid' => Str::uuid(), 'name' => 'Music', 'user_id' => 1, 'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['uuid' => Str::uuid(), 'name' => 'Video', 'user_id' => 1, 'parent_id' => null, 'created_at' => now(), 'updated_at' => now()],
        ];

        Folder::insert($folders);
    }
}
