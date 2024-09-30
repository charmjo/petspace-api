<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MobileTest extends TestCase
{
    // refresh to know we're operating a clean database
    use RefreshDatabase;

    #[Test]
    public function login_existing_user ()
    {
        // create test user
        $user = User::create([
            'name' => 'Charmy Test',
            'email' => 'testy@test.com',
            'password' => bcrypt('secret')
        ]);

        $response = $this->post('api/sanctum/token', [
            'email' => $user->email,
            'password' => 'secret',
            'device_name' => 'iphone'
        ]);

        $response->assertSuccessful();
        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseHas('personal_access_tokens',[
            'name' => 'iphone',
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id
        ]);
    }

    #[Test]
    public function fetch_user ()
    {
        // Create test user
        $user = User::create([
            'name' => 'Charmy Test',
            'email' => 'testy@test.com',
            'password' => bcrypt('secret')
        ]);

        // Log in to get a token
        $response = $this->post('api/sanctum/token', [
            'email' => $user->email,
            'password' => 'secret',
            'device_name' => 'iphone'
        ]);

        // Assert the response is successful
        $response->assertSuccessful();
        $this->assertNotEmpty($response->getContent());

        // Extract the token from the response
        $token = json_decode($response->getContent())->token;

        // Now access the user route using the token
        $userResponse = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->get('/user');

        // Assert the user route returns the authenticated user's information
        $userResponse->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }


}
