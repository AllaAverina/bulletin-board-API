<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    public function test_user_can_register_with_correct_credentials(): void
    {
        $response = $this->postJson(route('register'), [
            'name' => 'User Name',
            'nickname' => 'user-nickame',
            'email' => 'user@examlpe.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'User Name',
            'nickname' => 'user-nickame',
            'email' => 'user@examlpe.com',
        ]);
    }

    public function test_user_cannot_register_with_incorrect_credentials(): void
    {
        $response = $this->postJson(route('register'), [
            'name' => 'User Name',
            'nickname' => 'user nickame',
            'email' => 'user@examlpe',
            'password' => 'password',
            'password_confirmation' => 'wrong_password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure(['errors',]);

        $this->assertDatabaseMissing('users', [
            'name' => 'User Name',
            'nickname' => 'user nickame',
            'email' => 'user@examlpe',
        ]);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->actingAs($user)->postJson(route('login'), [
            'email' => 'user@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'access_token',
                'token_type',
            ]);

        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->actingAs($user)->postJson(route('login'), [
            'email' => 'user@example.net',
            'password' => '12345678'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'error' => 'The provided credentials are incorrect.',
            ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('logout'));

        $response->assertNoContent();
    }
}
