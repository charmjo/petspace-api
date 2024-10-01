<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MobileRegistrationTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function a_user_can_register_and_receive_an_api_token()
    {
        // Send a registration request
        $response = $this->postJson('/api/register', [
            'name' => 'JohnDoe Tester',
            'email' => 'jdoe.test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Assert that the response is successful
        $response->dump();
        $response->assertStatus(201)
            ->assertJson(['token']);

        // Assert the user exists in the database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
