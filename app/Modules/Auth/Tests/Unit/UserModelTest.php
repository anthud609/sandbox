<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Omni\Core\Modules\Auth\Models\User;

class UserModelTest extends TestCase
{
    private $userModel;

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

    public function testFindById()
    {
        $user = $this->userModel->findById(1);
        
        $this->assertIsArray($user);
        $this->assertEquals('testuser', $user['username']);
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userModel->findById(999);
        
        $this->assertFalse($user);
    }

    public function testFindByEmail()
    {
        $user = $this->userModel->findByEmail('test@example.com');
        
        $this->assertIsArray($user);
        $this->assertEquals(1, $user['id']);
    }

    public function testFindByEmailNotFound()
    {
        $user = $this->userModel->findByEmail('notfound@example.com');
        
        $this->assertFalse($user);
    }
}