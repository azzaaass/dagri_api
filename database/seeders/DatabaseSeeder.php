<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Post::create(
            [
                'user_id' => '1',
                'image_url' => '',
                'category' => 'Kehilangan',
                'status' => 'Belum Selesai',
                'description' => 'hallo rek',
                'contact' => '08123456789'
            ]
        );
    }
}
