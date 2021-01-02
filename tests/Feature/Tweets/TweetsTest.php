<?php

namespace Tests\Feature\Tweets;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TweetsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateTweet()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->postJson(route('tweet.create'), [
            "tweet" => "test tweet"
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['message', 'data' =>  ['tweet' ,  'user_id']]
        ]);

        $this->assertDatabaseHas('tweets', ['tweet' => 'test tweet',  'user_id' => $user->id]);
    }

    public function testGetTweets()
    {
        $user = factory(User::class)->create();
        $user_two = factory(User::class)->create();
        $user->following()->attach($user_two->id);

        factory(Tweet::class)->create(['tweet' => 'test tweet', 'user_id' => $user_two]);

         $response = $this->actingAs($user)->getJson(route('timeline'));
         $response->assertStatus(200)
            ->assertJsonStructure(['data' => [0 => ['tweet' , 'user_id']]]);
    }
}
