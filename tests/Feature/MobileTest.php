<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MobileLoginTest extends TestCase
{
    // refresh to know we're operating a clean database
    use RefreshDatabase;

    /** @test */
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

}
