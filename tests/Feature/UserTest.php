<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test Create User
     */
    public function testCreateUser(): void
    {
        $response = User::factory()->create();
        $this->assertDatabaseHas("users", $response->toArray());
    }

    /**
     * Test Get Users
     */
    public function testGetUsers()
    {
        $response = User::factory()->make();
        $this->assertInstanceOf(User::class, $response);
    }

    /**
     * Test Update User
     */
    public function testUpdateUser(){
        $response = User::factory()->create();
        $response->update([
            "name" => "test",
            "email" => "test",
            "password" => "test",
            "image" => "test",
        ]);
        $this->assertDatabaseHas("users", $response->toArray());
    }

    /**
     * Test Delete User
     */
    public function testDeleteUser(){
        $response = User::factory()->create();
        $response->delete();
        $this->assertDatabaseMissing("users", $response->toArray());
    }
}
