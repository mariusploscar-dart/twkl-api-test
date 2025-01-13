<?php

namespace App\Services\UserTypes;

use App\Models\User;

interface UserTypeInterface
{
    public function buildMessage(User $user): string;
    public function sendEmail(User $user);
}
