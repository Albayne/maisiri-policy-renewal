<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/Auth.php';

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function loginForm(): void
    {
        if (Auth::user()) {
            header('Location: index.php?action=dashboard');
            exit;
        }
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Username and password are required.';
            header('Location: index.php?action=login');
            exit;
        }

        $user = $this->userModel->findByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            Auth::login([
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]);
            header('Location: index.php?action=dashboard');
            exit;
        }

        $_SESSION['error'] = 'Invalid credentials or account inactive.';
        header('Location: index.php?action=login');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: index.php?action=login');
        exit;
    }
}
