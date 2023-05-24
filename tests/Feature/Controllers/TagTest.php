<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    public function test_view_tags(): void
    {
        $response = $this->getJson(route('tags.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'tag_id',
                        'name',
                        'slug',
                        'posts_count',
                    ]
                ],
            ]);
    }

    public function test_search_tags_by_name(): void
    {
        $response = $this->getJson(route('tags.index', ['q' => fake()->randomLetter(),]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'tag_id',
                        'name',
                        'slug',
                        'posts_count',
                    ]
                ],
            ]);
    }

    public function test_show_tag_and_posts(): void
    {
        $response = $this->getJson(route('tags.show', Tag::get()->random()->slug));

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
                    ]
                ],
                'links',
                'meta',
                'tag' => [
                    'tag_id',
                    'name',
                    'slug',
                ],
            ]);
    }
}
