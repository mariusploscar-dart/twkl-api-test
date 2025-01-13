<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\UserTypes\DefaultType;
use App\Services\UserTypes\StudentType;
use App\Services\UserTypes\TeacherType;
use App\Services\UserTypes\UserTypeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Sends a confirmation email.
     *
     * @return void
     */
    public function sendConfirmationEmail(): void
    {
        // Get the UserTypeInterface based on the user type
        $userType = $this->getUserType();

        // Send the email
        $userType->sendEmail($this);
    }

    /**
     * Returns the appropriate UserTypeInterface based on the user's type.
     *
     * @return UserTypeInterface The resolved UserTypeInterface implementation.
     */
    private function getUserType(): UserTypeInterface
    {
        switch ($this->type) {
            case 'student':
                return new StudentType();
            case 'teacher':
                return new TeacherType();
            case 'parent':
            case 'private_tutor':
            default:
                return new DefaultType();
        }
    }
}
