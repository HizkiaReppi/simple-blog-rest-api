<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User One',
            'username' => 'testuserone',
            'email' => 'testone@example.com',
            'password' => bcrypt('testing123')
        ]);

        User::factory()->create([
            'name' => 'Test User Two',
            'username' => 'testusertwo',
            'email' => 'testtwo@example.com',
            'password' => bcrypt('testing123')
        ]);

        Category::factory()->create([
            'name' => 'Test Category One',
            'slug' => 'test-category-one',
            'user_id' => 1,
        ]);

        Category::factory()->create([
            'name' => 'Test Category Two',
            'slug' => 'test-category-two',
            'user_id' => 2,
        ]);

        Post::factory(20)->create();
    }
}
