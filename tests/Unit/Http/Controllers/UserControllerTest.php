<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_signup_valid_data()
    {
        // Create a valid request data
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'user_type' => 'student',
        ];

        // Send a POST request to the signUp endpoint
        $response = $this->post('/api/signup', $data);

        // Assert that the response status is 201 (Created)
        $response->assertStatus(201);

        // Assert that a new user was created in the database
        $this->assertDatabaseHas('users', [
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'type' => $data['user_type'],
        ]);
    }

    public function test_signup_invalid_data()
    {
        // Create an invalid request data (missing required fields)
        $data = [
            'email' => $this->faker->unique()->safeEmail,
            'user_type' => 'student',
        ];

        // Send a POST request to the signUp endpoint
        $response = $this->post('/api/signup', $data);

        // Assert that the response status is 400 (Bad Request)
        $response->assertStatus(400);

        // Assert that the response contains validation errors
        $response->assertJsonStructure([
            'error' => [
                'first_name',
                'last_name',
            ],
        ]);
    }
}