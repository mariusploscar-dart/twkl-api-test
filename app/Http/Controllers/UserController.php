<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Handles user sign-up requests.
     *
     * @param Request $request The incoming request object containing the user's sign-up data.
     *
     * @return \Illuminate\Http\JsonResponse Returns a JSON response with appropriate status code and message.
     *
     * @throws \Exception If an error occurs while creating the user.
     */
    public function signUp(Request $request)
    {
        // Return immediately if request is from a blocked IP address
        if ($this->isIpBlocked($request->ip())) {
            return response()->json(['error' => 'Your IP address (' . $request->ip() . ') is blocked'], 403);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email:rfc,dns|unique:users',
            'user_type' => 'required|in:student,teacher,parent,private_tutor',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Validate first_name and last_name for invalid characters
        if (
            !$this->validateName($request->first_name . $request->last_name)
        ) {
            return response()->json(['error' => 'Special characters are not allowed'], 400);
        }

        try {
            // Generate a random password
            $password = Hash::make('your_password_here');

            // Create a new user with the generated password
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'type' => $request->user_type,
                'password' => $password,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create user' . $e->getMessage()], 500);
        }

        // todo: send notification email


        return response()->json(['message' => 'User signed up successfully'], 201);
    }

    /**
     * Validates a given string to ensure it only contains allowed characters.
     *
     * @param string $string The string to validate.
     *
     * @return bool Returns true if the string is valid, false otherwise.
     *
     * @throws \Exception If the regular expression fails to compile.
     */
    private function validateName($string)
    {
        // Define the allowed characters in the name
        $allowedCharacters = '/^[a-zA-Z\s\']+$/';

        // Check if the name contains any invalid characters
        if (!preg_match($allowedCharacters, $string)) {
            return false;
        }

        return true;
    }

    /**
     * Checks if a given IP address is blocked.
     *
     * @param string $ip The IP address to check.
     *
     * @return bool Returns true if the IP address is blocked, false otherwise.
     */
    private function isIpBlocked($ip)
    {
        // Define the list of blocked IP addresses
        $blockedIPs = [
            // Get blocked IP address from .env config for testing purposes
            // todo possibly improve this using a json config file
            env('TEST_BLOCKED_IP_ADDRESS', '0.0.0.0'),
        ];

        // Check if the given IP address is in the blocked IPs array
        return in_array($ip, $blockedIPs);
    }
}
