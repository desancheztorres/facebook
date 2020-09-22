<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostToTimelineTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
   public function a_user_can_post_a_text_post()
   {
       $this->withoutExceptionHandling();
       $this->actingAs($user = User::factory()->create(), 'api');

       $response = $this->post('/api/posts', [
           'data' => [
               'type' => 'posts',
               'attributes' => [
                   'body' => 'Testing Body',
               ]
           ]
       ]);

       $post = Post::first();

       $response->assertStatus(201);
   }
}
