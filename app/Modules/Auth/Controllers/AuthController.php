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

        // Pass error to the view
        include __DIR__ . '/../Views/LoginView.php';
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
}