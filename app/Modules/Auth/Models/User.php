<?php

declare(strict_types=1);

namespace Omni\Core\Modules\Auth\Models;

class User
{
    // Hardcoded users for demo
    private $users = [
        [
            'id' => 1,
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
        ],
        [
            'id' => 2,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ],
    ];

    public function authenticate($email, $password)
    {
        foreach ($this->users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                return $user;
            }
        }

        return false;
    }

    public function findById($id)
    {
        foreach ($this->users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }

        return false;
    }

    public function findByEmail($email)
    {
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        return false;
    }
}
