<?php
namespace Omni\Core\Modules\Auth\Controllers;

use Omni\Core\Modules\Auth\Models\User;

class AuthController 
{
    private $userModel;

    public function __construct() 
    {
        $this->userModel = new User();
    }

    public function showLogin() 
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        $error = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        echo $this->renderLoginForm($error);
    }

    public function processLogin() 
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Email and password are required';
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->authenticate($email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: /');
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid email or password';
            header('Location: /login');
            exit;
        }
    }

    public function logout() 
    {
        session_destroy();
        header('Location: /');
        exit;
    }

    private function renderLoginForm($error = '') 
    {
        return '<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
        .error { color: red; margin-bottom: 15px; }
        .back-link { margin-top: 15px; }
    </style>
</head>
<body>
    <h2>Login</h2>
    ' . ($error ? '<div class="error">' . htmlspecialchars($error) . '</div>' : '') . '
    <form method="POST" action="/login">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="back-link">
        <a href="/">Back to Home</a>
    </div>
    <div style="margin-top: 20px; padding: 10px; background: #f0f0f0; border-radius: 4px;">
        <strong>Test Credentials:</strong><br>
        Email: test@example.com<br>
        Password: password123
    </div>
</body>
</html>';
    }
}