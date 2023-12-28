<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;
    public function testRegister()
    {
        $data = [
            'name' => 'TestJ',
            'email' => 'testJ@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', ['email' => 'testJ@example.com']);
    }

    public function testLogin()
    {
         User::factory()->create([
            'email' => 'testJ@example.com',
            'password' => bcrypt('password123'),
        ]);

        $data = [
            'email' => 'testJ@example.com',
            'password' => 'password123',
        ];

        $response = $this->post('/api/login', $data);

        $response->assertStatus(200);
    }

    public function testLogout()
    {
        $user = User::factory()->create();

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->post('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully']);
    }
}
