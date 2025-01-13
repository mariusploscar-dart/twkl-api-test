<?php

namespace Tests\Unit\Http\Controllers;

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
            // Avoid invalidating test emails
            'email' => str_replace('example', 'test', $this->faker->unique()->safeEmail),
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

    public function test_signup_missing_data()
    {
        // Create an invalid request data (missing required fields)
        $data = [
            // Avoid invalidating test emails
            'email' => str_replace('example', 'test', $this->faker->unique()->safeEmail),
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

    public function test_signup_invalid_name()
    {
        // Create an invalid request data (missing required fields)
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => 'Invalid Last Name12345',
            // Avoid invalidating test emails
            'email' => str_replace('example', 'test', $this->faker->unique()->safeEmail),
            'user_type' => 'student',
        ];

        // Send a POST request to the signUp endpoint
        $response = $this->post('/api/signup', $data);

        // Assert that the response status is 400 (Bad Request)
        $response->assertStatus(400);

        // Assert that the response contains validation errors
        $response->assertJsonStructure([
            'error',
        ]);
    }

    public function test_signup_invalid_email()
    {
        // Create an invalid request data (missing required fields)
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => 'not!valid@email',
            'user_type' => 'student',
        ];

        // Send a POST request to the signUp endpoint
        $response = $this->post('/api/signup', $data);

        // Assert that the response status is 400 (Bad Request)
        $response->assertStatus(400);

        // Assert that the response contains validation errors
        $response->assertJsonStructure([
            'error' => [
                'email'
            ],
        ]);
    }

    public function test_signup_invalid_user_type()
    {
        // Create a valid request data with an invalid user type
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'user_type' => 'invalid_user_type',
        ];

        // Send a POST request to the signUp endpoint
        $response = $this->post('/api/signup', $data);

        // Assert that the response status is 400 (Bad Request)
        $response->assertStatus(400);

        // Assert that the response contains validation errors
        $response->assertJsonStructure([
            'error' => [
                'user_type'
            ],
        ]);
    }

    public function test_signup_blocked_ip()
    {
        // Set the blocked IP address in the environment variable
        $blockedIp = '127.0.0.1';
        putenv("TEST_BLOCKED_IP_ADDRESS=$blockedIp");

        // Create a valid request data
        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'user_type' => 'student',
        ];

        // Send a POST request to the signUp endpoint from the blocked IP address
        $response = $this->withHeaders([
            'X-FORWARDED-FOR' => $blockedIp,
        ])->post('/api/signup', $data);

        // Assert that the response status is 403 (Forbidden)
        $response->assertStatus(403);

        // Assert that the response contains an error message indicating the blocked IP address
        $response->assertJsonStructure([
            'error',
        ]);
    }
}
