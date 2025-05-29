<?php

declare(strict_types=1);

namespace Omni\Core\Modules\Auth\Controllers;

use Omni\Core\Modules\Auth\Models\User;

class AuthController
{
    private $userModel;

    private $testMode = false;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Enable test mode to prevent actual redirects
     *
     * @param mixed $testMode
     */
    public function setTestMode($testMode = true)
    {
        $this->testMode = $testMode;
    }

    /**
     * Redirect helper that can be disabled in test mode
     *
     * @param mixed $location
     */
    private function redirect($location)
    {
        if ($this->testMode) {
            // In test mode, just set a flag instead of redirecting
            $_SESSION['_test_redirect'] = $location;

            return;
        }

        header('Location: ' . $location);
        exit;
    }

    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');

            return;
        }

        $error = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        // Pass error to the view
        include __DIR__ . '/../Views/LoginView.php';
    }

    public function processLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Email and password are required';
            $this->redirect('/login');

            return;
        }

        $user = $this->userModel->authenticate($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->redirect('/');
        } else {
            $_SESSION['login_error'] = 'Invalid email or password';
            $this->redirect('/login');
        }
    }

    public function logout()
    {
        if ($this->testMode) {
            // In test mode, just clear the session variables instead of destroying
            unset($_SESSION['user_id'], $_SESSION['username']);
            $_SESSION['_test_redirect'] = '/';

            return;
        }

        session_destroy();
        header('Location: /');
        exit;
    }
}
