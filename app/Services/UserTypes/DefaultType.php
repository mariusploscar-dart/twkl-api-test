<?php

namespace App\Services\UserTypes;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DefaultType implements UserTypeInterface
{
    /**
     * Sends an email to the specified user.
     *
     * @param User $user The user to whom the email will be sent.
     */
    public function sendEmail(User $user): void
    {
        // Build the message
        $content = $this->buildMessage($user);
        $message = new Mailable($content);

        // Send the email - uncomment if needed
        // Mail::to($user->email)->send($message);

        // Log the event
        Log::info("Email sent to <$user->email> with the following message: \"$content\"");
    }

    /**
     * Builds the email message.
     *
     * @param array $data The data to be used for the message.
     * @return string The message to be sent.
     */
    public function buildMessage(User $user): string
    {
        return "Welcome to our platform, $user->name!";
    }
}
