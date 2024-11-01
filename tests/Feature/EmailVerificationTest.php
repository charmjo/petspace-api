<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @group skip
 */
class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function verify_email_address()
    {
        // Arrange
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'Testeringgg',
            'email' => 'tester@test.com',
            'password' => bcrypt('password')
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        // Act
        $response = $this->get($url);

        // Assert
        $response->assertSuccessful();

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    #[Test]
    public function resend_verification_email () {
        //Arrange
        //1. fake the notif
        Notification::fake();

        //2. create the user
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'Testeringgg',
            'email' => 'tester@test.com',
            'password' => bcrypt('password')
        ]);

        //3. fake the authentication by letting sanctum act as the user
        Sanctum::actingAs($user);

        //Act
        $response = $this->post(route('verification.send'));

        // Log::debug($response->all());

        //Assert
       $response->assertSuccessful();

    }
}
