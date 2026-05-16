<?php
session_start();

class Auth
{
    public static function requireAuth(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();
        $userRole = $_SESSION['user']['role'];
        if ($userRole !== $role && $userRole !== 'admin') {
            http_response_code(403);
            require __DIR__ . '/../views/error/403.php';
            exit;
        }
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return (self::user()['role'] ?? '') === 'admin';
    }

    public static function isOfficer(): bool
    {
        return in_array(self::user()['role'] ?? '', ['officer', 'admin']);
    }

    public static function login(array $user): void
    {
        $_SESSION['user'] = $user;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
    }
}
