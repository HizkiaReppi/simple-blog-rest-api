<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LogoutControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Truncate the users table before each test
        User::truncate();
    }

    /** @test */
    public function it_logs_out_a_user_and_revokes_tokens()
    {
        $user = User::factory()->create([
            'name' => 'Testing',
            'username' => 'testing',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $token = $user->createToken('TestToken')->accessToken;

        Passport::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertJson([
            'success' => true,
            'message' => 'Logout Success!',
        ]);

        $this->assertDatabaseMissing('oauth_access_tokens', [
            'id' => $token,
        ]);
    }

    /** @test */
    public function it_returns_an_error_if_unable_to_logout()
    {
        $response = $this->postJson('/api/logout');

        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);

        $response->assertStatus(401);
    }
}
