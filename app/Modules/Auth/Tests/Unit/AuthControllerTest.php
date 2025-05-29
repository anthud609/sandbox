<?php
namespace Omni\Core\Modules\Auth\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Omni\Core\Modules\Auth\Controllers\AuthController;

class AuthControllerTest extends TestCase
{
    private AuthController $authController;

    protected function setUp(): void
    {
        // Start session for testing
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->authController = new AuthController();
        $this->authController->setTestMode(true); // Enable test mode to prevent redirects
    }

    protected function tearDown(): void
    {
        // Clear session and POST data
        $_SESSION = [];
        $_POST = [];
    }

    public function testProcessLoginWithValidCredentials()
    {
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password123';

        $this->authController->processLogin();

        // Check that session was set correctly
        $this->assertEquals(1, $_SESSION['user_id']);
        $this->assertEquals('testuser', $_SESSION['username']);
        $this->assertEquals('/', $_SESSION['_test_redirect']); // Should redirect to home
    }

    public function testProcessLoginWithInvalidCredentials()
    {
        $_POST['email'] = 'wrong@example.com';
        $_POST['password'] = 'wrongpassword';

        $this->authController->processLogin();

        $this->assertEquals('Invalid email or password', $_SESSION['login_error']);
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertEquals('/login', $_SESSION['_test_redirect']); // Should redirect back to login
    }

    public function testProcessLoginWithMissingEmail()
    {
        $_POST['email'] = '';
        $_POST['password'] = 'password123';

        $this->authController->processLogin();

        $this->assertEquals('Email and password are required', $_SESSION['login_error']);
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertEquals('/login', $_SESSION['_test_redirect']);
    }

    public function testProcessLoginWithMissingPassword()
    {
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = '';

        $this->authController->processLogin();

        $this->assertEquals('Email and password are required', $_SESSION['login_error']);
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertEquals('/login', $_SESSION['_test_redirect']);
    }

    public function testLogout()
    {
        // Set up logged in state
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'testuser';

        $this->authController->logout();

        // In test mode, session vars should be unset but session not destroyed
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertArrayNotHasKey('username', $_SESSION);
        $this->assertEquals('/', $_SESSION['_test_redirect']);
    }

    public function testShowLoginWhenNotLoggedIn()
    {
        // Make sure user is not logged in
        unset($_SESSION['user_id']);

        // Capture output
        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        // Should show login form
        $this->assertStringContainsString('<form method="POST" action="/login">', $output);
        $this->assertStringContainsString('name="email"', $output);
        $this->assertStringContainsString('name="password"', $output);
    }

    public function testShowLoginWhenLoggedIn()
    {
        // Set logged in state
        $_SESSION['user_id'] = 1;
        $_SESSION['username'] = 'testuser';

        $this->authController->showLogin();

        // Should redirect to home page
        $this->assertEquals('/', $_SESSION['_test_redirect']);
    }

    public function testShowLoginWithError()
    {
        $_SESSION['login_error'] = 'Test error message';
        unset($_SESSION['user_id']); // Make sure not logged in

        ob_start();
        $this->authController->showLogin();
        $output = ob_get_clean();

        // Should display error and clear it from session
        $this->assertStringContainsString('Test error message', $output);
        $this->assertArrayNotHasKey('login_error', $_SESSION);
    }
}