<?php

namespace Tests\Feature\Follower;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FollowersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFollowUser()
    {
        $user = factory(User::class)->create();
        $user_two = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson(route('follow', ['user_id' => $user->id]));
        $response->assertStatus(400);

        $response = $this->actingAs($user)->postJson(route('follow', ['user_id' => $user_two->id]));
        $response->assertStatus(200);

    }
}
