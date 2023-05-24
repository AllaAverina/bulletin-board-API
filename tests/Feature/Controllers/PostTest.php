<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    public function test_view_posts(): void
    {
        $response = $this->getJson(route('posts.index'));

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
            ]);
    }

    public function test_search_posts_by_title(): void
    {
        $response = $this->getJson(route('posts.index', ['q' => fake()->randomLetter(),]));

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
            ]);
    }

    public function test_search_posts_by_tags(): void
    {
        $response = $this->getJson(route('posts.index', ['tags' => [Tag::get()->random()->id, Tag::get()->random()->id],]));

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
            ]);
    }

    public function test_sort_posts(): void
    {
        $response = $this->getJson(route('posts.index', ['sort' => 'price', 'order' => 'desc']));

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
            ]);
    }

    public function test_search_and_sort_posts(): void
    {
        $response = $this->getJson(route('posts.index', ['q' => fake()->randomLetter(), 'tags' => [Tag::get()->random()->id], 'sort' => 'title',]));

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
            ]);
    }

    public function test_show_post(): void
    {
        $response = $this->getJson(route('posts.show', Post::get()->random()->id));

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
                'post' => [
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
                    ]
                ]
            ]);
    }

    public function test_store_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('posts.store'), [
                'title' => 'test title',
                'price' => 100.50,
                'description' => 'test description',
                'tags' => [1, 2],
            ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
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
                ]
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'test title',
            'price' => 100.50,
            'description' => 'test description',
            'user_id' => $user->id,
        ]);
    }

    public function test_update_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id,]);

        $response = $this->actingAs($user)
            ->putJson(route('posts.update', $post->id), [
                'title' => 'new test title',
                'tags' => [1, 2,],
            ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
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
                ]
            ]);

        $this->assertDatabaseMissing('posts', ['title' => $post->title, 'user_id' => $user->id,])
            ->assertDatabaseHas('posts', ['title' => 'new test title', 'user_id' => $user->id,])
            ->assertDatabaseHas('post_tag', ['post_id' => $post->id, 'tag_id' => 1,])
            ->assertDatabaseHas('post_tag', ['post_id' => $post->id, 'tag_id' => 2,]);
    }

    public function test_destroy_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->deleteJson(route('posts.destroy', $post->id));

        $response->assertNoContent();

        $this->assertModelMissing($post);
    }
}
