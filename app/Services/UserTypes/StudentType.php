<?php

namespace App\Services\UserTypes;

use App\Models\User;

class StudentType extends DefaultType implements UserTypeInterface
{
    /**
     * Override parent method to build a specific message for this user type
     *
     * @param array $data The data to be used for the message.
     * @return string The message to be sent.
     */
    public function buildMessage(User $user): string
    {
        return "Welcome to our learning community, $user->name! You will have the opportunity to learn from our best teachers!";
    }
}
