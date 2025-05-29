<?php

declare(strict_types=1);

namespace Omni\Core\Modules\Auth\Tests\Unit;

use Omni\Core\Modules\Auth\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    private User $userModel;

    protected function setUp(): void
    {
        $this->userModel = new User();
    }

    public function testAuthenticateWithValidCredentials()
    {
        $user = $this->userModel->authenticate('test@example.com', 'password123');

        $this->assertIsArray($user);
        $this->assertEquals(1, $user['id']);
        $this->assertEquals('testuser', $user['username']);
        $this->assertEquals('test@example.com', $user['email']);
    }

    public function testAuthenticateWithInvalidCredentials()
    {
        $user = $this->userModel->authenticate('wrong@example.com', 'wrongpassword');

        $this->assertFalse($user);
    }

    public function testAuthenticateWithValidEmailButWrongPassword()
    {
        $user = $this->userModel->authenticate('test@example.com', 'wrongpassword');

        $this->assertFalse($user);
    }

    public function testAuthenticateWithEmptyCredentials()
    {
        $user = $this->userModel->authenticate('', '');

        $this->assertFalse($user);
    }

    public function testFindById()
    {
        $user = $this->userModel->findById(1);

        $this->assertIsArray($user);
        $this->assertEquals('testuser', $user['username']);
        $this->assertEquals('test@example.com', $user['email']);
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userModel->findById(999);

        $this->assertFalse($user);
    }

    public function testFindByIdWithZero()
    {
        $user = $this->userModel->findById(0);

        $this->assertFalse($user);
    }

    public function testFindByEmail()
    {
        $user = $this->userModel->findByEmail('test@example.com');

        $this->assertIsArray($user);
        $this->assertEquals(1, $user['id']);
        $this->assertEquals('testuser', $user['username']);
    }

    public function testFindByEmailNotFound()
    {
        $user = $this->userModel->findByEmail('notfound@example.com');

        $this->assertFalse($user);
    }

    public function testFindByEmailWithEmptyString()
    {
        $user = $this->userModel->findByEmail('');

        $this->assertFalse($user);
    }

    public function testFindAdminUser()
    {
        $user = $this->userModel->authenticate('admin@example.com', 'admin123');

        $this->assertIsArray($user);
        $this->assertEquals(2, $user['id']);
        $this->assertEquals('admin', $user['username']);
        $this->assertEquals('admin@example.com', $user['email']);
    }
}
