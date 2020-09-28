<?php

namespace Tests\Feature;

use App\Models\Friend;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FriendsTest extends TestCase {

    use RefreshDatabase;

    /** @test * */
    public function a_user_can_send_a_friend_request()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($user = User::factory()->create(), 'api');
        $anotherUser = User::factory()->create();

        $response = $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);

        $friendRequest = Friend::first();

        $this->assertNotNull($friendRequest);
        $this->assertEquals($anotherUser->id, $friendRequest->friend_id);
        $this->assertEquals($user->id, $friendRequest->user_id);

        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => null,
                ],
                'links' => [
                    'self' => url($anotherUser->path()),
                ],
            ],
        ]);
    }

    /** @test * */
    public function only_valid_users_can_be_friend_requested()
    {
        $this->actingAs($user = User::factory()->create(), 'api');

        $response = $this->post('/api/friend-request', [
            'friend_id' => 123,
        ])->assertStatus(404);

        $this->assertNull(Friend::first());

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'User Not found',
                'detail' => 'Unable to locate the user with the given information.',
            ],
        ]);
    }

    /** @test * */
    public function friend_requests_can_be_accepted()
    {
        $this->actingAs($user = User::factory()->create(), 'api');
        $anotherUser = User::factory()->create();

        $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);

        $response = $this->actingAs($anotherUser, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1,
            ])->assertStatus(200);

        $friendRequest = Friend::first();

        $this->assertNotNull($friendRequest->confirmed_at);
        $this->assertInstanceOf(Carbon::class, $friendRequest->confirmed_at);
        $this->assertEquals(now()->startOfSecond(), $friendRequest->confirmed_at);
        $this->assertEquals(1, $friendRequest->status);
        $response->assertJson([
            'data' => [
                'type' => 'friend-request',
                'friend_request_id' => $friendRequest->id,
                'attributes' => [
                    'confirmed_at' => $friendRequest->confirmed_at->diffForHumans(),
                ],
                'links' => [
                    'self' => url($anotherUser->path()),
                ],
            ],
        ]);
    }

    /** @test * */
    public function only_valid_friend_requests_can_be_accepted()
    {
        $anotherUser = User::factory()->create();

        $response = $this->actingAs($anotherUser, 'api')
            ->post('/api/friend-request-response', [
                'user_id' => 123,
                'status' => 1,
            ])->assertStatus(404);

        $this->assertNull(Friend::first());

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Request Not found',
                'detail' => 'Unable to locate the friend request with the given information.',
            ],
        ]);

    }

    /** @test * */
    public function only_the_recipient_can_accept_a_friend_request()
    {
        $this->actingAs($user = User::factory()->create(), 'api');
        $anotherUser = User::factory()->create();

        $this->post('/api/friend-request', [
            'friend_id' => $anotherUser->id,
        ])->assertStatus(200);

        $response = $this->actingAs(User::factory()->create(), 'api')
            ->post('/api/friend-request-response', [
                'user_id' => $user->id,
                'status' => 1,
            ])->assertStatus(404);

        $friendRequest = Friend::first();
        $this->assertNull($friendRequest->confirmed_at);
        $this->assertNull($friendRequest->status);

        $response->assertJson([
            'errors' => [
                'code' => 404,
                'title' => 'Friend Request Not found',
                'detail' => 'Unable to locate the friend request with the given information.',
            ],
        ]);
    }

    /** @test */
    public function a_friend_id_is_required_for_friend_requests()
    {
        $response = $this->actingAs($user = User::factory()->create(), 'api')
            ->post('/api/friend-request', [
                'friend_id' => '',
            ])->assertStatus(422);

        $responseString = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('friend_id', $responseString['errors']['meta']);
    }
}
