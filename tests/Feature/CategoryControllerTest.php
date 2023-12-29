<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'name' => 'Testing',
            'username' => 'testing',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        Passport::actingAs($user);
    }

    /** @test */
    public function it_returns_paginated_list_of_categories()
    {
        $categories = Category::factory(20)->create();

        $response = $this->json('GET', '/api/categories');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'current_page',
                'data' => [],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
        ]);

        $response->assertJsonFragment(['per_page' => 10]);
        $response->assertJsonFragment(['total' => 20]);
    }

    /** @test */
    public function it_shows_category_details()
    {
        $category = Category::factory()->create();

        $response = $this->json('GET', '/api/categories/' . $category->slug);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Detail Category!',
            'data' => $category->toArray(),
        ]);
    }

    // Add more tests for store, update, and destroy methods...

    /** @test */
    public function it_deletes_a_category()
    {
        $category = Category::factory()->create();

        $response = $this->json('DELETE', '/api/categories/' . $category->slug);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['slug' => $category->slug]);
    }
}
