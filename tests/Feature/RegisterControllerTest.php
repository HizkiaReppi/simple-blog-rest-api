<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        // Truncate the users table before each test
        User::truncate();
    }


    /** @test */
    public function it_requires_a_name()
    {
        $response = $this->json('POST', '/api/register', [
            'username' => 'Testing Register',
            'email' => $this->faker->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(400)
             ->assertJson(['name' => ['The name field is required.']]);
    }

    /** @test */
    public function it_requires_a_username()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Testing Register',
            'email' => $this->faker->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(400)
             ->assertJson(['username' => ['The username field is required.']]);
    }

    /** @test */
    public function it_requires_an_email()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Testing Register',
            'username' => 'Testing Register',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['email' => ['The email field is required.']]);
    }

    /** @test */
    public function it_requires_a_valid_email()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Testing Register',
            'username' => 'Testing Register',
            'email' => 'invalid_email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['email' => ['The email field must be a valid email address.']]);
    }

    /** @test */
    public function it_requires_a_password()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Testing Register',
            'username' => 'Testing Register',
            'email' => $this->faker->safeEmail,
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['password' => ['The password field is required.']]);
    }

    /** @test */
    public function it_requires_a_minimum_password_length()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Testing Register',
            'username' => 'testingregister',
            'email' => $this->faker->safeEmail,
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['password' => ['The password field must be at least 8 characters.']]);
    }

    /** @test */
    public function it_registers_a_user()
    {
        $userData = [
            'name' => 'Testing Register',
            'username' => 'testingregister',
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->json('POST', '/api/register', $userData);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Register Success!',
                 ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'username' => $userData['username'],
            'email' => $userData['email'],
        ]);
    }
}
