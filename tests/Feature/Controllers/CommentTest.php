<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    public function test_store_comment(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(
                route('posts.comments.store', Post::get()->random()->id),
                ['body' => 'test body',],
            );

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'comment_id',
                    'body',
                    'created_at',
                    'updated_at',
                    'post_id',
                    'user_id',
                    'user_nickname',
                ]
            ]);

        $this->assertDatabaseHas('comments', ['body' => 'test body', 'user_id' => $user->id]);
    }

    public function test_update_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->putJson(route('comments.update', $comment->id), [
                'body' => 'new test body',
            ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'comment_id',
                    'body',
                    'created_at',
                    'updated_at',
                    'post_id',
                    'user_id',
                    'user_nickname',
                ]
            ]);

        $this->assertDatabaseMissing('comments', ['body' => $comment->body,])
            ->assertDatabaseHas('comments', ['body' => 'new test body',]);
    }

    public function test_destroy_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson(route('comments.destroy', $comment->id));

        $response->assertNoContent();

        $this->assertModelMissing($comment);
    }
}
