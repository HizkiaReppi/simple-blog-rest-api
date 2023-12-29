<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        // Truncate the users table before each test
        User::truncate();
    }

    /** @test */
    public function it_requires_an_email()
    {
        $response = $this->json('POST', '/api/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(400)
            ->assertJson(['email' => ['The email field is required.']]);
    }

    /** @test */
    public function it_requires_a_valid_email()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'invalid_email',
            'password' => 'password123',
        ]);

        $response->assertStatus(400)
            ->assertJson(['email' => ['The email field must be a valid email address.']]);
    }

    /** @test */
    public function it_requires_a_password()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(400)
            ->assertJson(['password' => ['The password field is required.']]);
    }

    /** @test */
    public function it_fails_to_log_in_a_user_with_invalid_credentials()
    {
        $response = $this->postJson(route('login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'invalid_password',
        ]);

        $response->assertStatus(400)
            ->assertJson(['success' => false, 'message' => 'Invalid email or password.']);
    }

     /** @test */
    public function it_logs_in_a_user()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'name' => 'Testing',
            'username' => 'testing',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Attempt to log in with valid credentials
        $response = $this->json('POST', '/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login Success!',
            ])
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
                'token',
            ]);
    }
}
