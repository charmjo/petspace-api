<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MobileRegistrationTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function register_new_user()
    {
        Notification::fake();
        $response = $this->postJson('api/create-user', [
            'first_name' => 'Test',
            'last_name' => 'Testeringgg',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
 
        $response->assertSuccessful();
 
        $user = User::where('email', 'test@test.com')->first();
        Notification::assertSentTo($user, VerifyEmail::class);
 
        $this->assertNotEmpty($response->getContent());
        $this->assertDatabaseHas('users', ['email' => 'test@test.com']);
    //    $this->assertDatabaseHas('personal_access_tokens', ['name' => 'iphone']);
    }
}
