<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function signUp(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
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

        // todo: check for blocked ips

        // todo: send notification email

        return response()->json(['message' => 'User signed up successfully'], 201);
    }
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
}
