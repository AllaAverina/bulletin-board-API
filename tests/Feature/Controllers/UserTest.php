<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    public function test_view_users(): void
    {
        $response = $this->getJson(route('users.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'user_id',
                        'name',
                        'nickname',
                        'email',
                        'created_at',
                        'updated_at',
                        'posts_count',
                        'comments_count',
                    ]
                ],
                'links',
                'meta',
            ]);
    }

    public function test_search_users_by_name_or_nickname(): void
    {
        $response = $this->getJson(route('users.index', ['q' => fake()->randomLetter(),]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'user_id',
                        'name',
                        'nickname',
                        'email',
                        'created_at',
                        'updated_at',
                        'posts_count',
                        'comments_count',
                    ]
                ],
                'links',
                'meta',
            ]);
    }

    public function test_show_user(): void
    {
        $response = $this->getJson(route('users.show', User::get()->random()->nickname));

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

    public function test_show_user_and_posts(): void
    {
        $response = $this->getJson(route('users.show', [User::get()->random()->nickname, 'posts']));

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

    public function test_show_user_and_comments(): void
    {
        $response = $this->getJson(route('users.show', [User::get()->random()->nickname, 'comments']));

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
