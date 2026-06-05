<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function a_user_can_register_via_api()
    {
        $this->withoutExceptionHandling();
        $response = $this->json('POST', '/api/auth/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'msg' => 'Registrasi berhasil! Silakan periksa email Anda untuk verifikasi.'
                 ]);

        $this->assertDatabaseHas('users', [
            'username' => 'johndoe',
            'email' => 'johndoe@example.com'
        ]);

        $user = User::where('username', 'johndoe')->first();
        $this->assertTrue($user->hasRole('REGISTERED_USER'));
        $this->assertNotNull($user->profile);
        $this->assertEquals('John Doe', $user->profile->display_name);
    }

    /** @test */
    public function a_user_cannot_login_without_email_verification()
    {
        // Register user (unverified by default)
        $user = User::create([
            'name' => 'Unverified User',
            'username' => 'unverified',
            'email' => 'unverified@example.com',
            'password' => 'secret123',
        ]);
        $user->assignRole('REGISTERED_USER');

        $response = $this->json('POST', '/api/auth/login', [
            'username' => 'unverified',
            'password' => 'secret123',
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'status' => false,
                     'msg' => 'Email Anda belum diverifikasi. Silakan cek email Anda.'
                 ]);
    }

    /** @test */
    public function a_verified_user_can_login()
    {
        $user = User::create([
            'name' => 'Verified User',
            'username' => 'verified',
            'email' => 'verified@example.com',
            'password' => 'secret123',
        ]);
        $user->assignRole('REGISTERED_USER');
        $user->markEmailAsVerified();

        $response = $this->json('POST', '/api/auth/login', [
            'username' => 'verified',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'msg',
                     'data' => [
                         'access_token',
                         'token_type',
                         'expires_in',
                         'user'
                     ]
                 ]);
    }
}
