<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserSignIn()
    {
        $user = factory(User::class)->create();

        $response = $this->postJson(route('auth.login'), ['email' => $user->email, 'password' => 'password']);
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'email', 'image'], 'token']);
        $this->assertAuthenticatedAs($user);
    }

    public function testUserSignUp()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api');

        $response = $this->postJson(route('auth.sign_up'), [
            'name' => 'admin', 'email' => 'email@gmail.com', 'password' => 'password', 'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'email', 'image'], 'token']);

        $this->assertAuthenticatedAs($user);
    }
}
