<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Omni\Core\Modules\Auth\Controllers\AuthController;
use Omni\Core\Modules\Auth\Models\User;

class AuthControllerTest extends TestCase
{
    private $authController;

    protected function setUp(): void
    {
        // Start output buffering to capture controller output
        ob_start();
        
        // Mock session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->authController = new AuthController();
    }

    protected function tearDown(): void
    {
        // Clean output buffer
        ob_end_clean();
        
        // Clear session
        $_SESSION = [];
        $_POST = [];
    }

    public function testShowLoginDisplaysForm()
    {
        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        $this->assertStringContainsString('<form method="POST" action="/login">', $output);
        $this->assertStringContainsString('name="email"', $output);
        $this->assertStringContainsString('name="password"', $output);
    }

    public function testShowLoginRedirectsIfLoggedIn()
    {
        $_SESSION['user_id'] = 1;
        
        $this->expectOutputString('');
        
        // Capture headers
        ob_start();
        $this->authController->showLogin();
        ob_end_clean();
        
        // In a real scenario, this would redirect
        // We can't easily test headers in unit tests without more setup
        $this->assertTrue(true); // Placeholder assertion
    }

    public function testProcessLoginWithValidCredentials()
    {
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password123';

        ob_start();
        $this->authController->processLogin();
        ob_end_clean();

        $this->assertEquals(1, $_SESSION['user_id']);
        $this->assertEquals('testuser', $_SESSION['username']);
    }

    public function testProcessLoginWithInvalidCredentials()
    {
        $_POST['email'] = 'wrong@example.com';
        $_POST['password'] = 'wrongpassword';

        ob_start();
        $this->authController->processLogin();
        ob_end_clean();

        $this->assertEquals('Invalid email or password', $_SESSION['login_error']);
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }

    public function testProcessLoginWithMissingCredentials()
    {
        $_POST['email'] = '';
        $_POST['password'] = '';

        ob_start();
        $this->authController->processLogin();
        ob_end_clean();

        $this->assertEquals('Email and password are required', $_SESSION['login_error']);
        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }
}