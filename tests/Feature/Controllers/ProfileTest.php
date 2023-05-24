<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    public function test_show_auth_user_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('profile'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'user_id',
                    'name',
                    'nickname',
                    'email',
                    'created_at',
                    'updated_at',
                    'posts_count',
                    'comments_count',
                ]
            ]);
    }

    public function test_show_auth_user_profile_and_posts(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('profile', 'posts'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'post_id',
                        'title',
                        'price',
                        'description',
                        'created_at',
                        'updated_at',
                        'user_id',
                        'user_nickname',
                        'tags' => [
                            '*' => [
                                'tag_id',
                                'name',
                                'slug',
                            ]
                        ],
                        'comments_count',
                    ]
                ],
                'links',
                'meta',
                'user' => [
                    'user_id',
                    'name',
                    'nickname',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function test_show_auth_user_profile_and_comments(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('profile', 'comments'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'comment_id',
                        'body',
                        'created_at',
                        'updated_at',
                        'post_id',
                        'user_id',
                        'user_nickname',
                    ]
                ],
                'links',
                'meta',
                'user' => [
                    'user_id',
                    'name',
                    'nickname',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }
}
