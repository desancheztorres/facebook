<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanViewProfileTest extends TestCase
{
   use RefreshDatabase;

   /** @test **/
   public function a_user_can_view_user_profiles()
   {
       $this->actingAs($user = User::factory()->create(), 'api');
       $post = Post::factory()->create();

       $response = $this->get('/api/users/' . $user->id);

       $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'type' => 'users',
                'user_id' => $user->id,
                'attributes' => [
                    'name' => $user->name,
                ],
            ],
            'links' => [
                'self' => url($user->path())
            ]
        ]);

   }
}
