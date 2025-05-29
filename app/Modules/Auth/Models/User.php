<?php

declare(strict_types=1);

namespace Omni\Core\Modules\Auth\Models;

class User
{
    /**
     * @var array<int, array{id:int, username:string, email:string, password:string}>
     */
    private array $users = [
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

    /**
     * @param string $email
     * @param string $password
     *
     * @return array<string, mixed>|false
     */
    public function authenticate(string $email, string $password): array|false
    {
        foreach ($this->users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                return $user;
            }
        }

        return false;
    }

    /**
     * @param int $id
     *
     * @return array<string, mixed>|false
     */
    public function findById(int $id): array|false
    {
        foreach ($this->users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }

        return false;
    }

    /**
     * @param string $email
     *
     * @return array<string, mixed>|false
     */
    public function findByEmail(string $email): array|false
    {
        foreach ($this->users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        return false;
    }
}
