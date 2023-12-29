<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        // Create a user and authenticate with Passport
        $user = User::factory()->create([
            'name' => 'Testing',
            'username' => 'testing',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        
        $token = $user->createToken('TestToken')->accessToken;
        
        Passport::actingAs($user);

        Post::truncate();
        User::truncate();
    }

    /**
     * Test to get paginated list of articles.
     *
     * @return void
     */
    public function test_it_returns_paginated_list_of_articles()
    {
        // Create some dummy data in the database
        Post::factory(20)->create();

        // Make a GET request to the endpoint
        $response = $this->json('GET', '/api/posts');

        // Assert the response status code is 200
        $response->assertStatus(200);

        // Assert the response has the correct structure
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

        // Assert the correct number of items per page
        $response->assertJsonFragment(['per_page' => 10]);

        // Assert the correct number of total items
        $response->assertJsonFragment(['total' => 20]);
    }

     /** @test */
    public function it_returns_post_details_if_found()
    {
        // Create a post for testing
        $post = Post::factory()->create();

        // Hit the show endpoint with the post's slug
        $response = $this->json('GET', '/api/posts/' . $post->slug);

        // Assert the response status code is 200
        $response->assertStatus(200);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'title',
                'slug',
                'content',
                'image',
                'user_id',
                'category_id',
                'created_at',
                'updated_at',
            ],
        ]);

        // Assert the response contains the post details
        $response->assertJsonFragment(['id' => $post->id, 'title' => $post->title, 'slug' => $post->slug]);
    }

    /** @test */
    public function it_returns_404_if_post_not_found()
    {
        // Hit the show endpoint with a non-existing post's slug
        $response = $this->json('GET', '/api/posts/non-existing-slug');

        // Assert the response status code is 404
        $response->assertStatus(404);
    }
}
