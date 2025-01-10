<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_attributes()
    {
        $user = new User();
        $fillable = $user->getFillable();

        $this->assertEquals([
            'name',
            'email',
            'password',
            'user_type',
        ], $fillable);
    }

    public function test_hidden_attributes()
    {
        $user = new User();
        $hidden = $user->getHidden();

        $this->assertEquals([
            'password',
            'remember_token',
        ], $hidden);
    }

    public function test_casts_attributes()
    {
        $user = new User();
        $casts = $user->getCasts();

        $this->assertEquals([
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'id' => 'int',
        ], $casts);
    }
}